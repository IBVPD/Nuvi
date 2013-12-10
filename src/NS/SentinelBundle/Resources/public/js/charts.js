var drawGoogleCharts = function()
{
    if(google_charts)
    {
        l = google_charts.length;
        
        for(i=0; i<l; i++)
        {
            g = google_charts[i];
            g['chart'].draw(g['data'], g['options']);
        }
    }
};

var pushGoogleCharts = function(c)
{
    google_charts.push(c);
    drawGoogleCharts();
};