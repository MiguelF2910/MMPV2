from fastapi import FastAPI
from app.routes import router
from fastapi.staticfiles import StaticFiles
from fastapi.responses import FileResponse
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
# Ruta al directorio del frontend
frontend_path = os.path.join(os.path.dirname(__file__), "view")

# Verificar que la carpeta 'frontend' existe
if not os.path.exists(frontend_path):
    raise RuntimeError(f"Directory '{frontend_path}' does not exist")

# Montar los archivos estáticos desde la carpeta 'frontend'
# Montar carpetas de archivos estáticos
app.mount("/css", StaticFiles(directory="view/css"), name="css")
app.mount("/js", StaticFiles(directory="view/js"), name="js")
app.mount("/img", StaticFiles(directory="view/img"), name="img")


# Servir el archivo index.html en la raíz
@app.get("/")
async def serve_index():
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
