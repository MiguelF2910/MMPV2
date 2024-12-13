import requests
from bs4 import BeautifulSoup

def get_prices(component_name: str):
    """
    Realiza scraping de precios y obtiene las imágenes para un componente.
    """
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36"
    }
    
    amazon_url = f"https://www.amazon.com.mx/s?k={component_name.replace(' ', '+')}"
    mercadolibre_url = f"https://listado.mercadolibre.com.mx/{component_name.replace(' ', '-')}"
    cyberpuerta_url = f"https://www.cyberpuerta.mx/index.php?cl=search&searchparam=pieza+{component_name.replace(' ', '+')}"

    prices = {"amazon": None, "mercadolibre": None, "cyberpuerta": None}
    images = {"amazon": None, "mercadolibre": None, "cyberpuerta": None}
    
    try:
        # Scraping Amazon
        amazon_response = requests.get(amazon_url, headers=headers)
        amazon_soup = BeautifulSoup(amazon_response.content, "html.parser")
        amazon_price = amazon_soup.find("span", {"class": "a-price-whole"})
        if not amazon_price:
            amazon_price = amazon_soup.find("span", {"class": "a-offscreen"})
        if amazon_price:
            prices["amazon"] = float(amazon_price.text.replace("$", "").replace(",", ""))
        amazon_image = amazon_soup.find("img", {"class": "s-image"})
        if amazon_image and 'src' in amazon_image.attrs:
            images["amazon"] = amazon_image['src']

        # Scraping Mercado Libre
        mercadolibre_response = requests.get(mercadolibre_url, headers=headers)
        mercadolibre_soup = BeautifulSoup(mercadolibre_response.content, "html.parser")
        mercadolibre_price = mercadolibre_soup.find("span", {"class": "andes-money-amount__fraction"})
        if mercadolibre_price:
            prices["mercadolibre"] = float(mercadolibre_price.text.replace(",", ""))
        mercadolibre_image = mercadolibre_soup.find("img", {"class": "poly-component__picture"})
        if mercadolibre_image and 'src' in mercadolibre_image.attrs:
            images["mercadolibre"] = mercadolibre_image['src']

        # Scraping Cyberpuerta
        cyberpuerta_response = requests.get(cyberpuerta_url, headers=headers)
        cyberpuerta_soup = BeautifulSoup(cyberpuerta_response.content, "html.parser")
        cyberpuerta_price = cyberpuerta_soup.find("label", {"class": "price"})
        if cyberpuerta_price:
            prices["cyberpuerta"] = float(cyberpuerta_price.text.replace("$", "").replace(",", ""))
        cyberpuerta_image = cyberpuerta_soup.find("div", {"class": "cs-image"})
        if cyberpuerta_image and 'src' in cyberpuerta_image.attrs:
            images["cyberpuerta"] = cyberpuerta_image['src']
    except Exception as e:
        print(f"Error al realizar scraping: {e}")
    
    return prices, images
