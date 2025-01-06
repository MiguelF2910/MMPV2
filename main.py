from fastapi import FastAPI
from app.routes import router
import os  
import pymysql


app = FastAPI()


app.include_router(router)


db_host = os.getenv("DB_HOST", "sql109.infinityfree.com")  
db_user = os.getenv("DB_USER", "if0_38047788")
db_password = os.getenv("DB_PASSWORD", "a17LZJeFw8E")
db_name = os.getenv("DB_NAME", "if0_38047788")

def connectbd():    
    return pymysql.connect(
        host=db_host,
        user=db_user,
        password=db_password,
        database=db_name
    )
# Montar la carpeta "frontend" como archivos estáticos
frontend_path = os.path.join(os.path.dirname(__file__), "../view")
app.mount("/static", StaticFiles(directory=frontend_path), name="static")

# Ruta para servir el archivo index.html
@app.get("/")
async def serve_frontend():
    return FileResponse(os.path.join(frontend_path, "index.html"))

@app.get("/")
async def root():
    return {"message": "Chatbot backend running!"}

@app.get("/test-db")
async def test_db():
    try:
        connection = connectbd()
        cursor = connection.cursor()
        cursor.execute("SELECT DATABASE()")
        db = cursor.fetchone()
        connection.close()
        return {"message": f"Connected to database: {db}"}
    except Exception as e:
        return {"error": str(e)}
