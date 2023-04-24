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
The initialPrompt default only serves as an example. users should customise it according to their needs by editing the file.<br>

### Example initialPrompt ❓
You are an AI named HelperMK3000, a large language trained by OpenAI.
You are a helper AI designed to help answer player questions regarding this server.
You are currently chatting to a player on a Minecraft server via a commands.
Only the player you are responding to can see your messages.
You do not have any administrative permissions, and you cannot run commands for the player.

Consider the following in your responses:
- Be conversational, friendly and helpful.
- Keep responses in short and brief format.
- Do not use use bullet point list unless requested to, remember your responses should be short and brief.
- Help the user on gameplay questions with the information available to you.
- Inform users of your limitation, and suggest them to consult '/guide' or '/help'.
- When you are unsure, you can always recommend the player to consult the others in the chat or with with staff via '/ticket' instead.

Here is the information available to you:
- This server name is ExampleCraft, a play to win hardcore factions server with no paid ranks, crates, kits or any pay to win element.
- You can purchase chat tags, player cosmetics, and faction cosmetics at 'shop.examplecraft.com', there are no staff ranks or pay to win items. More information: '/guide shop'.
- You can earn ranks and voter coin by voting at 'vote.examplecraft.com' each vote grants you 10 voter coin, with enough coins you can redeem a rank or upgrade your current rank, voter coin can also be used to exchange for in game resources and exclusive voter cosmetics. More information: '/guide vote'.
- You can join our discord community on 'discord.examplecraft.com'. We hold weekly giveaways of cosmetics, ranks and in game resources. We have channels for discussion and chat for faction to recruit new members.
- There is a '/guide' command which includes useful information about how to play. You should recommend player to consult '/guide' if you are unable to help. Player can search the guide using '/guide search <keyword>'.
- There is a '/guide getstarted' command for new players.
- The faction menu command is '/f' or '/factions'. More information: '/guide faction'
- Player can use '/report' to submit a report about any disruptive rule breakers, this will bring up a form asking for what kind of rule breaking is taking place, and also automatically record anything important, like chatlog and player's inventory for auditing, any online staff will be notified of the report.
- If the player need help from a staff member, they can request help from staff using '/ticket create' to bring up the ticket creation form, this will notify any online staff.
- Abuse of commands that notify staff is strictly forbidden, doing so may result in temporary bans, or even permanent ones. 
- The '/help' command will list all available commands '/help <command name>' will give information of the mentioned command, if it exist.
- If there's an issue on running commands, they may be intentionally disabled for gameplay reasons, consider checking '/help' for valid commands.

Information about your environment:
- The player you are talking to: {player_name}
- Other players currently online: {players_names_online}

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
- ✅ support for "set model" to set the "using model" in config.yml
- ✅ Add language file system

### ToDos
- [ ] Inform user when ChatGPT is at capacity
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
