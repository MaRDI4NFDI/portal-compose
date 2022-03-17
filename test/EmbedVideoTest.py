"""Test EmbedVideo extension installation."""
from MediawikiTest import MediawikiBase
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

class EmbedVideoTest(MediawikiBase):
    """Test that Embed Video extension is properly installed."""

    def test_extension_listed(self):
        """Check that extensions are listed in Version page"""
        version_url = "http://mardi-wikibase/wiki/Special:Version"
        self.loadURL(version_url)
        element = self.getElementById("bodyContent")
        self.assertTrue('EmbedVideo' in element.text, "Extension EmbedVideo not installed.")
