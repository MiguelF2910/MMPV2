###
from meta_ai_api import MetaAI

ai = MetaAI()
response = ai.prompt(message="Que dia es hoy?")
##print(response) da un mensaje largo 


message = response.get('message','No')

print(message)

