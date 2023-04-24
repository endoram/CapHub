import pytest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys

@staticmethod
def test_login_valid_credentials():
    # Go to the login page
    options = webdriver.ChromeOptions()
    options.add_argument("--headless")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")
    options.binary_location = "/usr/bin/google-chrome"
    driver = webdriver.Chrome(options=options)
    driver.get("https://qa.caphub.org/index.php")

    # Find the CAP ID input element and fill it with a valid value
    capid_input = driver.find_element(by=By.NAME, value="capid")
    capid_input.send_keys("5555")

    # Find the password input element and fill it with a valid value
    password_input = driver.find_element(by=By.NAME, value="password")
    password_input.send_keys("hi")

    # Submit the login form
    password_input.send_keys(Keys.RETURN)

    # Verify that we were redirected to the protected/main.php page
    assert driver.current_url == "https://qa.caphub.org/protected/main.php"
    driver.quit()

@staticmethod
def test_login_invalid_credentials():
    options = webdriver.ChromeOptions()
    options.add_argument("--headless")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")
    options.binary_location = "/usr/bin/google-chrome"
    driver = webdriver.Chrome(options=options)

    # Go to the login page
    driver.get("https://qa.caphub.org/index.php")

    # Find the CAP ID input element and fill it with an invalid value
    capid_input = driver.find_element(by=By.NAME, value="capid")
    capid_input.send_keys("000000")

    # Find the password input element and fill it with an invalid value
    password_input = driver.find_element(by=By.NAME, value="password")
    password_input.send_keys("invalidpassword")

    # Submit the login form
    password_input.send_keys(Keys.RETURN)

    # Verify that we see an error message
    assert "Invalid Cap ID or password" in driver.page_source

@staticmethod
def test_login_missing_credentials():
    options = webdriver.ChromeOptions()
    options.add_argument("--headless")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")
    options.binary_location = "/usr/bin/google-chrome"
    driver = webdriver.Chrome(options=options)
    # Go to the login page
    driver.get("https://qa.caphub.org/index.php")

    # Submit the login form without entering any credentials
    submit_button = driver.find_element(by=By.CSS_SELECTOR, value="input[type='submit']")
    submit_button.click()

    # Verify that we see an error message
    assert "Invalid Cap ID or password" in driver.page_source
