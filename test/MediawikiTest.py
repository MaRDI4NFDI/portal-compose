"""Test core MediaWiki installation."""
from dockerSelenium.Base import Base
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException
import os


class MediawikiBase(Base):
    def _login(self):
        """
            Logins to the Wiki using the web UI.
            @raise TimeoutException when not successful.
        """
        login_url = "http://mardi-wikibase/wiki/Special:UserLogin"
        self.loadURL(login_url)
        self.getElementById('wpName1').clear()
        self.getElementById('wpName1').send_keys("Admin")
        self.getElementById('wpPassword1').clear()
        # get password from environment if given (i.e. in CI)
        if 'MW_ADMIN_PASS' in os.environ.keys():
            password = os.environ['MW_ADMIN_PASS']
        else:
            password = "change-this-password"
        self.getElementById('wpPassword1').send_keys(password) 
        self.getElementById('wpLoginAttempt').submit()
        # wait for login to redirect to another page (i.e. Home Page)
        WebDriverWait(self.driver, 5).until(EC.url_changes(login_url))


class MediawikiCoreTest(MediawikiBase):
    """
    Test for Mediawiki core.

    The test class extends Base.py.
    Base.py provides:
    * loadURL
    * getElementById
    * getUrlStatusCode
    
    URL's to containers are defined in 
    the 'container_name' parameters of each service in docker-compose.yml
    """

    def test1(self):
        """Test that Mediawiki is running."""
        status = self.getUrlStatusCode("http://mardi-wikibase")
        self.assertEquals(200, status, "Problem loading home page.")

    def test3(self):
        """Test that anonymous editing is disabled."""
        edit_url = 'http://mardi-wikibase/wiki/Main_Page?action=edit'
        self.loadURL(edit_url)
        page_text = self.getElementById('mw-content-text').text
        self.assertTrue(
            'You do not have permission to edit this page' in page_text, 
            "Anonymous editing has not been disabled")
