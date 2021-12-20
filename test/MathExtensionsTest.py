"""Test Math extensions installation."""
from MediawikiTest import MediawikiBase
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

class MathExtensionsTest(MediawikiBase):
    """Test that Math extensions are properly installed."""
    
    def test1(self):
        """Check that extensions are listed in Version page"""
        version_url = "http://mardi-wikibase/wiki/Special:Version"
        self.loadURL(version_url)
        element = self.getElementById("bodyContent")
        self.assertTrue('Render mathematical formulas' in element.text, "Extension Math not installed.")
        self.assertTrue('MathSearch' in element.text, "Extension Math not installed.")
     
    # This works only if the templates from mediawiki/extras are present
    #def test2(self):
    #    """Test that a Math search input form can be created."""
    #    self._login()
    #    # create or edit page Math_search_test
    #    test_page_url = "http://mardi-wikibase/wiki/Math_search_test?action=edit"
    #    self.loadURL(test_page_url)
    #    self.getElementById('wpTextbox1').clear() # clear the page
    #    self.getElementById('wpTextbox1').send_keys("{{search box}}")
    #    self.getElementById('wpTextbox1').submit()
    #    # wait for page to display
    #    WebDriverWait(self.driver, 10).until(EC.url_changes(test_page_url))
    #    # Check that input field is present
    #    elements = self.driver.find_elements_by_class_name("searchboxInput")
    #    self.assertTrue(len(elements) == 1, "No math search input field found.")
