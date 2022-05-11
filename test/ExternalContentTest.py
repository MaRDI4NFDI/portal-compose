"""Test ExternalContent extension installation."""
from MediawikiTest import MediawikiBase
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

class ExternalContentTest(MediawikiBase):
    """Test that ExternalContent extension is properly installed."""

    def test_extension_listed(self):
        """Check that extensions are listed in Version page"""
        version_url = "http://mardi-wikibase/wiki/Special:Version"
        self.loadURL(version_url)
        element = self.getElementById("bodyContent")
        self.assertTrue('External Content' in element.text, "Extension ExternalContent not installed.")
