<?php
namespace GLX20\PmGPT\task;

use GLX20\PmGPT\Main;
use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Config;

class GPTResponseTask extends AsyncTask {

    protected string $question;
    protected Config $config;
    private string $playerName;
    private string $filepath;
    public string $cainfo_path;
    public string $initialPrompt;


    public function __construct($question, Config $config, $playerName, $filepath, $cainfo_path, $initialPrompt) {
        $this->question = $question;
        $this->config = $config;
        $this->playerName = $playerName;
        $this->filepath = $filepath;
        $this->cainfo_path = $cainfo_path;
        $this->initialPrompt = $initialPrompt;
    }

    public function onRun():void {
        $ch = curl_init();
        $messages = array(array("role" => "system","content" => $this->initialPrompt));
        if (file_exists($this->filepath)) {
            $history = file_get_contents($this->filepath);
            $messages = array(...$messages, ...json_decode($history, true));
        }
        //Special thanks to Thunder from Pocketmine-MP Community Discord
        $messages[] = array("role"=>"user","content"=>$this->question);
        $url = 'https://api.openai.com/v1/chat/completions';
        $api_key = $this->config->get("OpenAiApiKey");
        $post_fields = array(
            "model" => "gpt-3.5-turbo",
            "messages" => $messages,
            "max_tokens" => (int)$this->config->get("maxTokens"),
            "temperature" => (int)$this->config->get("temperature")
        );
        $header  = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ];

        if($this->config->get("useInsecureConnection") === true){
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        if($this->config->get("usePluginCertFile") === true){
            curl_setopt($ch, CURLOPT_CAINFO, $this->cainfo_path);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $this->setResult('Error: ' . curl_error($ch));
        } else {
            $this->setResult(json_decode($result));
        }
        curl_close($ch);
    }

    public function onCompletion():void {
        $response = $this->getResult();
        if (isset($response->error) && isset($response->error->message)) {
            $message = $response->error->message;
            Main::getInstance()->getServer()->getPlayerExact($this->playerName)->sendMessage("§4ChatGPT: §a".$message);
            return;
        }
        if(!empty($response->choices[0]->message->content)) {
            $filepath = $this->filepath;
            $dir = Main::getInstance()->getDataFolder() . "temp/";
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            if (!file_exists($filepath)) {
                $data = array();
            } else {
                $json = file_get_contents($filepath);
                $data = json_decode($json, true);
            }
            $data[] = array("role" => "user", "content" => $this->question);
            $answer = trim($response->choices[0]->message->content);
            $data[] = array("role" => "assistant", "content" => $answer);
            $json = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents($filepath, $json);
            $chatHistory = '';
            foreach ($data as $entry) {
                if ($entry['role'] === 'user' || $entry['role'] === 'assistant') {
                    $role = ($entry['role'] === 'user') ? "§a$this->playerName" : '§cChatGPT';
                    $content = $entry['content'];
                    $chatHistory .= "$role: $content\n";
                }
            }

            $form = new CustomForm(function (Player $player, $data) use ($filepath) {
                if ($data === null) {
                    return false;
                }
                $question = $data[1];
                Main::getInstance()->getServer()->getAsyncPool()->submitTask(new GPTResponseTask($question, $this->config, $this->playerName, $filepath, $this->cainfo_path, $this->initialPrompt));
                Main::getInstance()->getServer()->getPlayerExact($this->playerName)->sendMessage("§4ChatGPT: §aPlease wait while i generate a response...");
                return false;
            });
            $form->setTitle("§l§2[ §aPm§4GPT§r §l§2]");
            $form->addLabel("Chat history:\n" . $chatHistory);
            $form->addInput("§3Whats your question ?\n");
            $form->sendToPlayer(Main::getInstance()->getServer()->getPlayerExact($this->playerName));
        }
    }

}
