/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function getColor(d) {
    return d > 1000 ? '#800026' :
           d > 500  ? '#BD0026' :
           d > 10  ? '#E31A1C' :
           d > 8  ? '#FC4E2A' :
           d > 6   ? '#FD8D3C' :
           d > 4   ? '#FEB24C' :
           d > 3   ? '#FED976' :
                      '#FFEDA0';
}

function style(feature) {
    return {
        weight: 3,
        opacity: 1,
        color: '#666',
        dashArray: '',
        fillOpacity: 0.7,
        fillColor: getColor(feature.properties.REGION_DATA)
    };
}

function zoomToFeature(e) {
    map.fitBounds(e.target.getBounds());
}


