from fastapi import FastAPI
from app.routes import router

app = FastAPI()

# Registrar las rutas del proyecto
app.include_router(router)

@app.get("/")
async def root():
    return {"message": "Chatbot backend running!"}
