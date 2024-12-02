# ocr.py
import sys
import pytesseract
from PIL import Image

# Set the path to Tesseract executable (if not in PATH)
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'  # Update path if necessary

def extract_text(image_path):
    try:
        img = Image.open(image_path)
        text = pytesseract.image_to_string(img)
        return text
    except Exception as e:
        return str(e)

if __name__ == '__main__':
    image_path = sys.argv[1]
    text = extract_text(image_path)
    print(text)
