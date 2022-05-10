"""Test YouTube extension installation."""
from MediawikiTest import MediawikiBase
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

class YouTubeTest(MediawikiBase):
    """Test that YouTube extension is properly installed."""

    def test_extension_listed(self):
        """Check that extensions are listed in Version page"""
        version_url = "http://mardi-wikibase/wiki/Special:Version"
        self.loadURL(version_url)
        element = self.getElementById("bodyContent")
        self.assertTrue('YouTube' in element.text, "Extension YouTube not installed.")
