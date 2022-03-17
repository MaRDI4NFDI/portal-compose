"""Test that monitoring tools are working"""
from MediawikiTest import MediawikiBase


class GrafanaTest(MediawikiBase):
    def test1(self):
        """Test that Grafana is running."""
        status = self.getUrlStatusCode("http://grafana:3000")
        self.assertEquals(200, status, "Problem loading Grafana start page.")

    def test2(self):
        """Test that Prometheus is running."""
        status = self.getUrlStatusCode("http://prometheus:9090")
        self.assertEquals(200, status, "Problem loading Prometheus start page.")
