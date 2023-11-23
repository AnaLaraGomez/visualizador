from selenium import webdriver
import time
driver1 = webdriver.Chrome()
driver1.set_window_position(1921, 0, windowHandle='current')
driver1.maximize_window()
driver1.get("http://localhost/visualizador/interfaz/index.html?alumno")
driver1.fullscreen_window()
driver2 = webdriver.Chrome()
driver2.set_window_position(0, 0, windowHandle='current')
driver2.maximize_window()
driver2.get("http://localhost/visualizador/interfaz/index.html?profesor")
driver2.fullscreen_window()
time.sleep(120)
