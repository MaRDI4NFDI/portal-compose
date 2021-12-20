"""Test Math extensions installation."""
from MediawikiTest import MediawikiBase
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

class WDQSTest(MediawikiBase):
    """Test that Wikidata query service properly installed."""
    
    def test1(self):
        """Test that WDQS frontend is running."""
        status = self.getUrlStatusCode("http://mardi-wdqs-frontend")
        self.assertEquals(200, status, "Problem loading wdqs frontend.")
