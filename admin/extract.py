import sys
import cv2
import pytesseract
from PIL import Image
import re

# Path to Tesseract executable
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

# Get the image path from the command line arguments
if len(sys.argv) < 2:
    print("Error: No image path provided.")
    sys.exit(1)

image_path = sys.argv[1]

# Load image
image = cv2.imread(image_path)

# Convert image to grayscale
gray_image = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

# Use Tesseract to extract text from image
extracted_text = pytesseract.image_to_string(gray_image)

# Print the extracted text (for debugging purposes)
print("Extracted Text: ", extracted_text)

# Regex to find the expiration date (in MM/DD/YYYY or similar formats)
matches = re.findall(r'(\d{2}[-/]\d{2}[-/]\d{4})', extracted_text)

if matches:
    expiration_date = matches[0]  # Assuming the first match is the expiration date
    print("Expiration Date Found: ", expiration_date)
else:
    print("No expiration date found.")
