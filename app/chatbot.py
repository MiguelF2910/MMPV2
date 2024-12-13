from meta_ai_api import MetaAI
import ast
import json

ai = MetaAI()

def process_user_input(user_input):
    """
    Envía el mensaje del usuario a la IA de Meta y procesa su respuesta.
    :param user_input: Mensaje del usuario.
    :return: Respuesta de la IA en formato dict.
    """
    from meta_ai_api import MetaAI  # Importar la IA desde la librería (asegúrate de que está configurada)
    
    ai = MetaAI()
    
    try:
        # Realizar la solicitud a la IA
        raw_response = ai.prompt(message=user_input)
        print("Raw AI Response:", raw_response)  # Imprime la respuesta cruda de la IA
        
        # Verificar si la respuesta es un string (probablemente JSON en texto)
        if isinstance(raw_response, str):
            try:
                # Intentar convertir la respuesta en un diccionario
                ai_response = json.loads(raw_response)
            except json.JSONDecodeError as e:
                print(f"Error decoding JSON: {e}")
                return {"error": "La IA devolvió una respuesta en un formato no válido."}
        elif isinstance(raw_response, dict):
            # Si ya es un diccionario, no hay que hacer nada
            ai_response = raw_response
        else:
            print(f"Unexpected AI response type: {type(raw_response)}")
            return {"error": "La IA devolvió una respuesta en un formato desconocido."}

        # Imprime la respuesta procesada para depuración
        print("Processed AI Response:", ai_response)
        return ai_response

    except Exception as e:
        print(f"Error interacting with the AI: {e}")
        return {"error": "Ocurrió un error al comunicarse con la IA."}
import ast

def process_ai_response(ai_response):
    """
    Procesa la respuesta de la IA para separar el mensaje principal y extraer los componentes sugeridos.
    :param ai_response: Respuesta de la IA en formato dict.
    :return: Un diccionario con 'message_from_ai' y 'suggested_components'.
    """
    try:
        # Imprime la respuesta completa de la IA para verificar su formato
        print("AI Full Response:", ai_response)

        # Verificar si el formato de la respuesta contiene el campo "message"
        if not isinstance(ai_response, dict):
            print("AI response is not a dictionary.")
            return {
                "message_from_ai": "La respuesta de la IA no tiene un formato válido.",
                "suggested_components": []
            }

        if "message" not in ai_response or not ai_response["message"]:
            print("No 'message' field in AI response or it is empty.")
            return {
                "message_from_ai": "No se recibió un mensaje válido de la IA.",
                "suggested_components": []
            }

        # Obtener el campo "message"
        message_from_ai = ai_response["message"]

        # Imprimir el mensaje completo para depuración
        print("AI Message Field:", message_from_ai)

        # Buscar el inicio y fin de la lista de componentes
        start_index = message_from_ai.find("[")
        end_index = message_from_ai.find("]")

        if start_index == -1 or end_index == -1:
            print("No components list found in AI message.")
            return {
                "message_from_ai": message_from_ai,
                "suggested_components": []
            }

        # Extraer la lista de componentes
        components_list_text = message_from_ai[start_index:end_index + 1]

        # Convertir el texto de la lista a una lista real utilizando `ast.literal_eval`
        try:
            suggested_components = ast.literal_eval(components_list_text)
        except Exception as e:
            print(f"Error parsing components list: {e}")
            suggested_components = []

        # Verificar si el resultado es una lista
        if not isinstance(suggested_components, list):
            print("Parsed components list is not a valid list.")
            suggested_components = []

        # Retornar el mensaje sin la lista y la lista de componentes
        return {
            "message_from_ai": message_from_ai[:start_index].strip(),
            "suggested_components": suggested_components
        }
    except Exception as e:
        print(f"Error processing AI response: {e}")
        return {
            "message_from_ai": "Ocurrió un error al procesar la respuesta de la IA.",
            "suggested_components": []
        }

