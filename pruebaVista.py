# archivo: consulta.py
from meta_ai_api import MetaAI

ai = MetaAI()
response = ai.prompt(message="¿Qué día es hoy?")
print(response)