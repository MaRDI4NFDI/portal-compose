"""Test Medik skin installation."""
from MediawikiTest import MediawikiBase
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

class SkinTest(MediawikiBase):
    """Test that SyntaxHighlight extension is properly installed."""

    def test_extension_listed(self):
        """Check that skins are listed in Version page"""
        version_url = "http://mardi-wikibase/wiki/Special:Version"
        self.loadURL(version_url)
        element = self.getElementById("bodyContent")
        self.assertTrue('Medik' in element.text, "Medik skin not installed.")
