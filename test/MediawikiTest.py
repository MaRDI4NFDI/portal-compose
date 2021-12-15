"""A simple test class example."""
from dockerSelenium.Base import Base
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException

class MediawikiCoreTest(Base):
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

    def test2(self):
        """Test that Mediawiki admin password has been changed."""
        try:
            # open login page
            login_url = "http://mardi-wikibase/wiki/Special:UserLogin"
            status = self.getUrlStatusCode(login_url)
            self.assertEquals(200, status, "Problem loading login page.")
            self.loadURL(login_url)
            current_url = self.driver.current_url
            # attemp login with user Admin and default password
            self.getElementById('wpName1').clear()
            self.getElementById('wpName1').send_keys("Admin")
            self.getElementById('wpPassword1').clear()
            self.getElementById('wpPassword1').send_keys("change-this-password") 
            self.getElementById('wpLoginAttempt').submit()
            # wait for login to redirect to another page (i.e. Home Page)
            WebDriverWait(self.driver, 5).until(EC.url_changes(current_url))
            # URL changed, so login with default password succeeded,
            # so test is BAD
            self.assertTrue(False, "Password was not changed")
            
        except TimeoutException:
            # Login reloaded the login page, 
            # the webdriver threw a timeout exception waiting for the URL to change,
            # login with default password did not succeed,
            # so test is GOOD
            self.assertTrue(True)

    def test3(self):
        """Test that anonymous editing is disabled."""
        edit_url = 'http://mardi-wikibase/wiki/Main_Page?action=edit'
        self.loadURL(edit_url)
        page_text = self.getElementById('mw-content-text').text
        self.assertTrue(
            'You do not have permission to edit this page' in page_text, 
            "Anonymous editing has not been disabled")
