#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Thu Feb 24 18:37:36 2022

@author: alvaro
"""
from MediawikiTest import MediawikiBase
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
import time

class SearchTest(MediawikiBase):
    def test_01(self):
        """Searches for a word, expects empty but error-free result."""
        home_url = "http://mardi-wikibase"
        self.loadURL(home_url)
        current_url = self.driver.current_url

        vue = self.driver.find_elements(By.XPATH,"//div[@id='p-search']/a")
        if vue:
            vue[0].click()
            time.sleep(10)
            print(self.driver.page_source)
            search_field = self.getElementByXPath("//input[@class='wvui-input__input']")
        else:
            search_field = self.getElementById('searchInput')

        search_field.clear()
        search_field.send_keys("boe")
        search_field.send_keys(Keys.ENTER)
        # wait for search to load
        WebDriverWait(self.driver, 5).until(EC.url_changes(current_url))
        search_results = self.getElementById('mw-content-text').text
        self.assertFalse('An error has occurred while searching' in search_results, 'Error while searching')        