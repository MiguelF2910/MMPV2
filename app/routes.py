from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
from app.chatbot import process_user_input
from app.scraper import get_prices

router = APIRouter()

# Modelo Pydantic para la solicitud
class UserInputRequest(BaseModel):
    user_input: str

@router.post("/chat")
async def chat_with_bot(data: UserInputRequest):
    """
    Endpoint para interactuar con el chatbot y realizar scraping de precios.
    """
    try:
        # 1. Procesar la entrada del usuario con el chatbot
        ai_response, suggested_components = process_user_input(data.user_input)

        # 2. Validar que se hayan generado sugerencias de componentes
        if not suggested_components:
            raise HTTPException(status_code=400, detail="El chatbot no generó componentes sugeridos.")

        # 3. Realizar scraping de precios para los componentes sugeridos
        total_price = 0
        component_prices = {}
        for component in suggested_components:
            prices = get_prices(component)  # Obtener precios del scraper
            component_prices[component] = prices
            total_price += prices.get("total", 0)  # Sumar el total de los precios

        # 4. Responder al cliente con la información del chatbot y los precios
        return {
            "message_from_ai": ai_response,
            "components": component_prices,
            "total_price": total_price
        }

    except Exception as e:
        # Manejo de errores generales
        raise HTTPException(status_code=500, detail=str(e))


