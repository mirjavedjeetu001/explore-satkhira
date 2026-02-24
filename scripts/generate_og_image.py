#!/usr/bin/env python3
"""
Generate OG Image for Explore Satkhira
1200x630 pixels (Facebook recommended)
Using English text only for better compatibility
"""

from PIL import Image, ImageDraw, ImageFont
import os

# Output path
output_path = "/home/javed/Desktop/javed/satkhira-web/public/images/og-image.png"
os.makedirs(os.path.dirname(output_path), exist_ok=True)

# Image dimensions (Facebook recommended)
WIDTH = 1200
HEIGHT = 630

# Colors (matching site theme - green)
PRIMARY_GREEN = (26, 71, 42)  # #1a472a
SECONDARY_GREEN = (45, 80, 22)  # #2d5016
GOLD = (212, 175, 55)  # #d4af37
WHITE = (255, 255, 255)
LIGHT_GREEN = (200, 230, 200)

def create_gradient_background(width, height, color1, color2):
    """Create a diagonal gradient background"""
    img = Image.new('RGB', (width, height))
    for y in range(height):
        for x in range(width):
            # Diagonal gradient
            ratio = (x + y) / (width + height)
            r = int(color1[0] * (1 - ratio) + color2[0] * ratio)
            g = int(color1[1] * (1 - ratio) + color2[1] * ratio)
            b = int(color1[2] * (1 - ratio) + color2[2] * ratio)
            img.putpixel((x, y), (r, g, b))
    return img

# Create base image with gradient
img = create_gradient_background(WIDTH, HEIGHT, PRIMARY_GREEN, SECONDARY_GREEN)
draw = ImageDraw.Draw(img)

# Load fonts - use DejaVuSans which supports English well
font_title = ImageFont.truetype("/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf", 85)
font_subtitle = ImageFont.truetype("/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf", 36)
font_tagline = ImageFont.truetype("/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf", 28)

# Draw decorative border
draw.rounded_rectangle((20, 20, WIDTH - 20, HEIGHT - 20), radius=20, outline=GOLD, width=4)
draw.rounded_rectangle((35, 35, WIDTH - 35, HEIGHT - 35), radius=15, outline=(150, 120, 40), width=1)

# Draw leaf icon (simple representation)
leaf_cx, leaf_cy = 180, HEIGHT // 2
# Main leaf shape
draw.ellipse((leaf_cx - 50, leaf_cy - 70, leaf_cx + 50, leaf_cy + 10), fill=GOLD)
# Stem
draw.line([(leaf_cx, leaf_cy + 10), (leaf_cx, leaf_cy + 50)], fill=GOLD, width=6)
# Leaf veins
draw.line([(leaf_cx - 30, leaf_cy - 25), (leaf_cx + 30, leaf_cy - 25)], fill=PRIMARY_GREEN, width=2)
draw.line([(leaf_cx, leaf_cy - 55), (leaf_cx, leaf_cy - 5)], fill=PRIMARY_GREEN, width=2)

# Draw main title - Explore Satkhira (English)
title = "Explore Satkhira"
bbox = draw.textbbox((0, 0), title, font=font_title)
text_width = bbox[2] - bbox[0]
draw.text(((WIDTH - text_width) // 2 + 60, HEIGHT // 2 - 90), title, fill=WHITE, font=font_title)

# Draw subtitle
subtitle = "Satkhira District Directory"
bbox = draw.textbbox((0, 0), subtitle, font=font_subtitle)
text_width = bbox[2] - bbox[0]
draw.text(((WIDTH - text_width) // 2 + 60, HEIGHT // 2 + 20), subtitle, fill=GOLD, font=font_subtitle)

# Draw tagline
tagline = "All information of Satkhira in one place"
bbox = draw.textbbox((0, 0), tagline, font=font_tagline)
text_width = bbox[2] - bbox[0]
draw.text(((WIDTH - text_width) // 2 + 60, HEIGHT // 2 + 75), tagline, fill=LIGHT_GREEN, font=font_tagline)

# Draw website URL at bottom
url = "exploresatkhira.com"
bbox = draw.textbbox((0, 0), url, font=font_subtitle)
text_width = bbox[2] - bbox[0]
draw.rounded_rectangle(((WIDTH - text_width) // 2 - 30, HEIGHT - 100, (WIDTH + text_width) // 2 + 30, HEIGHT - 50), radius=25, fill=(40, 60, 35), outline=GOLD, width=2)
draw.text(((WIDTH - text_width) // 2, HEIGHT - 92), url, fill=GOLD, font=font_subtitle)

# Save image
img.save(output_path, 'PNG', quality=95)
print(f"OG Image created: {output_path}")
