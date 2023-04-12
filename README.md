![Readme](https://user-images.githubusercontent.com/79281788/230729315-414e95a9-9d1e-4df9-adbc-40a72cf16a20.png)<br>
Have a conversation with the ```ChatGPT gpt-3.5-turbo model```<br>
Plugin for Pocketmine-MP API4+<br>

Works only with API-KEY<br>
https://platform.openai.com/account/api-keys<br>

The use of OpenAi ChatgGTP is limited in the free trial.<br>
You get a $5 grant per registration, after which you have to top up your credit or create a new account.<br>
The $5 grant are only valid for one month.<br>
Prices are per 1,000 tokens. You can think of tokens as pieces of words, where 1,000 tokens is about 750 words.<br>
$0.002 / 1K tokens.<br>

### Data storage 🗂
So that each user can have his own conversation. This plugin saves the chat history with ChatGPT in a .txt file<br>
This file is saved in plugin_data\PmGPT\temp\playername_chat.txt after the first question.<br>

### How to Start ? ▶
Just put your API-KEY in the config.yml and start the server.<br>
Only users with ```pmgpt.use``` permission can run the command ```/chatgpt```<br>
Depending on how busy ChatGPT is, an answer can take between 2 and 30+ seconds<br>

### Answer preview 🤖💬
***Question: tell me good names for factions***<br>
![descriptionUI](https://user-images.githubusercontent.com/79281788/231010759-e0425c13-3ddf-4c12-852e-c556e5a8bd20.png)<br>


### Features
- ✅ Add gpt-3.5-turbo model instead of text-davinci-002
- ✅ Add remembering previous questions
- ✅ Run response action on async task to prevent freeze on waiting for response
- ✅ Send a please wait message if the response takes a little longer
- ✅ Work with UI instead of Chat 

### ToDos
- [ ] Inform user when ChatGPT is at capacity
- [ ] Add SSL certificate by using https://github.com/Muqsit/CurlWithSSL
