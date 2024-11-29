import requests
from bs4 import BeautifulSoup

def get_prices(component_name: str):
    """
    Realiza scraping de precios para un componente en Amazon y Mercado Libre.
    """
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36"
    }
    
    # URLs de búsqueda para los sitios web
    amazon_url = f"https://www.amazon.com.mx/s?k={component_name.replace(' ', '+')}"
    mercadolibre_url = f"https://listado.mercadolibre.com.mx/{component_name.replace(' ', '-')}"
    
    prices = {"amazon": None, "mercadolibre": None, "total": 0}
    
    try:
        # Scraping de Amazon
        amazon_response = requests.get(amazon_url, headers=headers)
        amazon_soup = BeautifulSoup(amazon_response.content, "html.parser")

        # Intentar obtener el precio de la clase "a-price-whole"
        amazon_price = amazon_soup.find("span", {"class": "a-price-whole"})

        # Si no se encuentra, intentar con "a-offscreen"
        if not amazon_price:
            amazon_price = amazon_soup.find("span", {"class": "a-offscreen"})

        if amazon_price:
            prices["amazon"] = float(amazon_price.text.replace("$", "").replace(",", ""))
        
        # Scraping de Mercado Libre
        mercadolibre_response = requests.get(mercadolibre_url, headers=headers)
        mercadolibre_soup = BeautifulSoup(mercadolibre_response.content, "html.parser")
        mercadolibre_price = mercadolibre_soup.find("span", {"class": "andes-money-amount__fraction"})
        if mercadolibre_price:
            prices["mercadolibre"] = float(mercadolibre_price.text.replace(",", ""))
        
        # Calcular total
        prices["total"] = (prices["amazon"] or 0) + (prices["mercadolibre"] or 0)
    
    except Exception as e:
        print(f"Error al realizar scraping: {e}")
    
    return prices
