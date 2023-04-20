![Readme](https://user-images.githubusercontent.com/79281788/230729315-414e95a9-9d1e-4df9-adbc-40a72cf16a20.png)<br>
[![](https://poggit.pmmp.io/shield.api/PmGPT)](https://poggit.pmmp.io/p/PmGPT)
[![](https://poggit.pmmp.io/shield.state/PmGPT)](https://poggit.pmmp.io/p/PmGPT)
[![GitHub license](https://img.shields.io/badge/license-Apache-blue.svg)](
https://github.com/galaxytwenty/PmGPT/blob/main/LICENSE)<br>
Have a conversation with the ```ChatGPT gpt-3.5-turbo model```<br>
Plugin for Pocketmine-MP API4+<br>

Works only with API-KEY<br>
https://platform.openai.com/account/api-keys<br>

The use of OpenAi ChatgGTP is limited in the free trial.<br>
You get a $5 grant per registration, after which you have to top up your credit or create a new account.<br>
The $5 grant are only valid for one month.<br>
Prices are per 1,000 tokens. You can think of tokens as pieces of words, where 1,000 tokens is about 750 words.<br>
$0.002 / 1K tokens.<br>

### Data storage and use 🗂
So that each user can have his own conversation. This plugin saves the chat history with ChatGPT in a .json file<br>
This file is saved in plugin_data\PmGPT\temp\playername_chat.json after the first question.<br>
The user can delete this file himself for private reasons.<br>
<br>
PmGPT use libFormAPI to create the UI<br>

### How to Start ? ▶
Just put your ```API-KEY``` in the ```config.yml``` and start the server.<br>
Only users with ```pmgpt.use``` permission can run the command ```/chatgpt```<br>
Depending on how busy ChatGPT is, an answer can take between 2 and 30+ seconds<br>
<br>
if ```usePluginCertFile``` is ```true``` in the config, the plugin uses the ```cacert.pem``` from https://curl.haxx.se/ca/cacert.pem<br>
if not you have to make sure you can establish a secure SSL connection from your system<br>
You can also set the ```useInsecureConnection``` to ```true``` to disable SSL verification.<br>

### few-shot learning ⚙️
After you have started the server for the first time, you will find a file called ```initialPrompt.yml``` in the plugin_data/PmGPT folder.<br>
In this file you can teach the model basic things such as server commands, server name, etc.<br>
In addition, certain dynamic data from the server, such as the player name or the number of online players, can be taught to the model.<br>
My initialPrompt example is probably not the best and only serves as an example. users should customise it according to their needs by editing the file.<br>
You can find more information in the ```initialPrompt.yml``` file.<br>

### Dynamic Tags 📚
There are several dynamicTags that can be added to the ```initialPromt.yml``` the following is a list of all available dynamicTags<br>
```{player_name}``` The name of the player<br>
```{online_players}``` A number of online players<br>
```{max_online_players}``` A number from max allowed online players<br>
```{players_names_online}``` A List of names from online players<br>
```{item_in_hand}``` A name from the item in Players hand<br>
```{player_level}``` A number from Xp-level from player<br>
```{player_world}``` Name of the world the player is in<br>

### Features
- ✅ Add gpt-3.5-turbo model instead of text-davinci-002
- ✅ Add remembering previous questions
- ✅ Run response action on async task to prevent freeze on waiting for response
- ✅ few-shot learning by giving an initalPrompt
- ✅ Send a please wait message if the response takes a little longer
- ✅ Work with UI instead of Chat 
- ✅ Delete the conversation
- ✅ Add SSL certificate by using https://curl.haxx.se/ca/cacert.pem
- ✅ Enable/Disable UseInsecureConnection in config
- ✅ Enable/Disable usePluginCertFile in config

### ToDos
- [ ] Inform user when ChatGPT is at capacity
- [ ] Add language file system to support more languages
- [ ] a switch between chat or window mode would be good
- [ ] Add more dynamic tags, suggestions ?

### Answer preview 🤖💬
***Question: Whats your name ?***<br>
![whatsyourname](https://user-images.githubusercontent.com/79281788/233417540-7e1dfed0-5fae-48cd-96bf-b17cb552537b.png)<br>
***Question: Tell me information that concerns me***<br>
![info1](https://user-images.githubusercontent.com/79281788/233418476-31a3decc-62b1-4fdc-a2a1-f417a9c755f6.png)
<br>
***Question: Do you have any advice for a good redstone machine ?***<br>
![no redstone](https://user-images.githubusercontent.com/79281788/233418288-1ff3f4c6-c290-4a0b-bba8-6c6427cfd1b9.png)
<br>
***Question: Can i earn money here ?***<br>
![earn money](https://user-images.githubusercontent.com/79281788/233418107-37cff721-4322-4e88-9211-6c1c7d74008f.png)
<br>
***Question: Can i find more items like this in my hand ?***<br>
![more items like this](https://user-images.githubusercontent.com/79281788/233417847-932bb5b7-85f7-4d93-ade7-b7baed588276.png)
<br>
***Question: do you have a suggestion for good faction names ?, list me a few***<br>
![descriptionUI](https://user-images.githubusercontent.com/79281788/231010759-e0425c13-3ddf-4c12-852e-c556e5a8bd20.png)<br>
<br>
