"""Test Math extensions installation."""
from MediawikiTest import MediawikiBase

class WDQSTest(MediawikiBase):
    """Test that Wikidata query service properly installed."""
    
    def test1(self):
        """Test that WDQS frontend is running."""
        status = self.getUrlStatusCode("http://mardi-wdqs-frontend")
        self.assertEquals(200, status, "Problem loading wdqs frontend.")

    def test2(self):
        """Test that Quickstatements is running."""
        status = self.getUrlStatusCode("http://mardi-quickstatements")
        self.assertEquals(200, status, "Problem loading quickstatements.")
