from meta_ai_api import MetaAI

ai = MetaAI()

def process_user_input(user_input: str):
    """
    Procesa la entrada del usuario con la IA y genera sugerencias de componentes.
    """
    # Respuesta de la IA
    response = ai.prompt(message=user_input)

    # Simulación: Analizar la respuesta de la IA para generar componentes sugeridos
    # (Deberías adaptar esta parte a cómo responde tu IA de Meta)
    suggested_components = ["RTX 3060", "Ryzen 5 5600X"]  # Ejemplo estático

    return response, suggested_components

