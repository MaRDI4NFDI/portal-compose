from MediawikiTest import MediawikiBase

class LaTeXTest(MediawikiBase):
    LATEX_URL = 'http://latexml:8080'
    
    def test_1(self):
        """Check that LaTeX container is running"""
        status = self.getUrlStatusCode(LaTeXTest.LATEX_URL)
        self.assertEqual(200, status, "Problem connecting to latexml.")

    def test_2(self):
        """Check that LaTeX to mathml conversion works"""
        self.loadURL("{}/editor".format(LaTeXTest.LATEX_URL))
        # select "Equations" from the examples menu
        element = self.getElementByXPath("//select[@id='example_select']")
        self.assertTrue(element, "Editor doesn't seem to be loaded")
        all_options = element.find_elements_by_tag_name("option")
        for option in all_options:
            if option.get_attribute('value') == 'eqn':
                option.click()
        # check that the result of the conversion is rendered
        element = self.getElementByXPath("//table[@id='S1.Ex1']")
        math_elements = element.find_elements_by_tag_name("math")
        self.assertTrue(len(math_elements) > 0, "Conversion doesn't seem to work")
        

if __name__ == '__main__':
    LaTeXTest().test_1()
    LaTeXTest().test_2()