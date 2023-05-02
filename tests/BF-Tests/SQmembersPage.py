import pytest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys

@pytest.fixture
def browser():
    options = webdriver.ChromeOptions()
   # options.add_argument("--headless")
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")
    options.binary_location = "/usr/bin/google-chrome"
    driver = webdriver.Chrome(options=options)

    yield driver
    driver.quit()

def login(browser):
    browser.get("https://qa.caphub.org/index.php")
    # Find the CAP ID input element and fill it with a valid value
    capid_input = browser.find_element(by=By.NAME, value="capid")
    capid_input.send_keys("5555")
    # Find the password input element and fill it with a valid value
    password_input = browser.find_element(by=By.NAME, value="password")
    password_input.send_keys("hi")
    # Submit the login form
    password_input.send_keys(Keys.RETURN)
    # Verify that we were redirected to the protected/main.php page
  #  assert browser.current_url == "https://qa.caphub.org/protected/main.php"

def test_export_data(browser):
    login(browser)
    browser.get("https://qa.caphub.org/protected/sqmembers.php?export")
  #  export_button = browser.find_element(by=By.NAME, value="export")
  #  export_button.click()
    assert browser.current_url == "https://qa.caphub.org/protected/sqmembers.php"
    # Check that the file is downloaded successfully.

"""
def test_search_by_cap_id(browser):
    browser.get("https://qa.caphub.org/sqmembers.php?capid=12345")
    # Check that the search results are displayed correctly.

def test_search_by_first_name(browser):
    browser.get("https://qa.caphub.org/sqmembers.php?firstname=John")
    # Check that the search results are displayed correctly.

def test_search_by_last_name(browser):
    browser.get("https://qa.caphub.org/sqmembers.php?lastname=Doe")
    # Check that the search results are displayed correctly.

def test_search_by_privilege_level(browser):
    browser.get("https://qa.caphub.org/sqmembers.php?priv=1")
    # Check that the search results are displayed correctly.

def test_retire_members(browser):
    browser.get("https://qa.caphub.org/sqmembers.php?retire=1")
    retire_button = browser.find_element_by_id("retire1")
    retire_button.click()
    # Check that the members are retired successfully.

def test_revive_members(browser):
    browser.get("https://qa.caphub.org/sqmembers.php?retired=1")
    revive_button = browser.find_element_by_id("retire1")
    revive_button.click()
    # Check that the members are revived successfully.

def test_add_member(browser):
    browser.get("https://qa.caphub.org/sqmembers.php?addmember=1")
    # Fill out the form to add a member and check that the member is added successfully.

def test_generate_statistics_report(browser):
    browser.get("https://qa.caphub.org/sqmembers.php?statistics=1")
    # Check that the statistics report is generated successfully.

    browser.quit()

"""