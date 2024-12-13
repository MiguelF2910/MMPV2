from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
from app.chatbot import process_user_input, process_ai_response
from app.scraper import get_prices

router = APIRouter()

# Modelo Pydantic para la solicitud
class UserInputRequest(BaseModel):
    user_input: str

@router.post("/chat")
async def chat_with_bot(data: UserInputRequest):
    try:
        # Llamar al chatbot para obtener la respuesta
        ai_response = process_user_input(data.user_input)
        
        if "error" in ai_response:
            return {"error": ai_response["error"]}

        # Procesar la respuesta para separar el mensaje y los componentes
        processed_response = process_ai_response(ai_response)

        if not processed_response["suggested_components"]:
            return {
                "message_from_ai": processed_response["message_from_ai"],
                "components": {},
                "total_price": 0
            }

        # Realizar scraping para cada componente sugerido
        components_with_prices = {}
        components_with_images = {}
        total_price = 0

        for component in processed_response["suggested_components"]:
            prices, images = get_prices(component)
            components_with_prices[component] = prices
            components_with_images[component] = images

            # Calcular el precio más bajo para este componente
            component_prices = [price for price in prices.values() if price is not None]
            if component_prices:
                total_price += min(component_prices)  # Sumar solo el precio más bajo

        # Construir la respuesta final
        return {
            "message_from_ai": processed_response["message_from_ai"],
            "components": {
                component: {
                    "prices": components_with_prices[component],
                    "images": components_with_images[component]
                }
                for component in processed_response["suggested_components"]
            },
            "total_price": total_price
        }
    except Exception as e:
        print(f"Error in /chat endpoint: {e}")
        return {"error": "An unexpected error occurred. Please try again later."}
