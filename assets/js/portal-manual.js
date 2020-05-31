/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// intro target data to be add when no indicator data is available
var intro_target_no_ind = [
    {
        element: document.getElementById("alert-message"),
        intro: "Information about indicator availability"
    }
];

// intro target data to be add when indicator data is available
var intro_target_ind = [    
    {
        element: document.getElementById("indicator-info"),
        intro: "Indicator card with relevant information"
    },
    {
        element: document.getElementById("indicator-title"),
        intro: "The name of inidcator"
    },
    {
        element: document.getElementById("indicator-description"),
        intro: "Description about the indicator"
    },
    {
        element: document.getElementById("explore-btn"),
        intro: "A button to explore indicator charts, tables and maps"
    }
];

// introjs chart tab steps
var intro_chart_steps = [
    {
        element: document.getElementById("nav-home-tab"),
        intro: "Tab option to display chart data."  
    },
    {
        element: document.getElementById("data-filter"),
        intro: "Data filters: use for filtering data. E.g Disaggregation, Total and Others (NDP targets and Groups)"
    },
    {
        element: document.getElementById("show_labels"),
        intro: "Shows the data values labels on top of the bar, column, line"
    },
    {
        element: document.getElementById("chart-heading"),
        intro: "Different chart options"
    },
    {
        element: document.getElementById("chart-body"),
        intro: "Area to display the chart data"
    }
];

// introjs table tab steps
var intro_table_steps = [
    {
        element: document.getElementById("nav-chartTable-tab"),
        intro: "Tab option to display table data"
    },
    {
        element: document.getElementById("btn-download"),
        intro: "Data table download options: CSV, PDF and an option to print"
    },
    {
        element: document.getElementById("dashboardChartTable"),
        intro: "Table to display indicator data"
    }
];

// introjs maps tab steps
var intro_map_steps = [
    {
        element: document.getElementById("nav-map-tab"),
        intro: "Tab option to display map data"
    },
    {
        element: document.getElementById("btn-year-filter"),
        intro: "Year filter for map data"
    },
    {
        element: document.getElementById("btn-map-other-filter"),
        intro: "Indicator data filters for maps"
    },
    {
        element: document.getElementById("btn-clear"),
        intro: "Clear all selected filter data"
    },
    {
        element: document.getElementById("btn-year-filter-compare"),
        intro: "Compare multiple years using maps"
    },
    {
        element: document.getElementById("map"),
        intro: "Map area: wher data for each region is displayed"
    }
];

var intro_help = introJs().setOptions({
    steps: [
        {
            element: document.getElementById("startTourBtn"),
            intro: "Need help? Click this button to get a quick tour of the current page"
        }
    ]
});

// create the introjs instance
// initialise the instance for Home page
var intro_home = introJs().setOptions({
    steps: [
        {
            element: document.getElementById("navbar-brand"),
            intro: "SDG portal Home."
        },
        {
            element: document.getElementById("sdg-button"),
            intro: "This is a navigation menu for all the goals and indicators."
        },
        {
            element: document.getElementById("search"),
            intro: "Can't find the what you're looking for? \nUse our search engine to find it."  
        },
        {
            element: document.getElementById("info-box"),
            intro: "All information about an SDG goal."
        },
        {
            element: document.getElementById("goal-title"),
            intro: "Goal name, number and icon."
        },
        {
            element: document.getElementById("reported_1"),
            intro: "Number of indicators as a percentage (%) which has data available for reporting (Charts, Tables or Maps) and visible to public."
        },
        {
            element: document.getElementById("progress_1"),
            intro: "Number of indicators as a percentage (%) that has data pending verification and publishing."
        },
        {
            element: document.getElementById("exploring_1"),
            intro: "Number of indicators as a percentage (%) without data"
        }
    ]
});

// A tour of the goals page
var intro_goal = introJs().setOptions({
    steps: [
        {
            element: document.getElementById("goal-title"),
            intro: "The name and icon of the goal"
        },
        {
            element: document.getElementById("goal-summary"),
            intro: "Summary of the goal."  
        },
        {
            element: document.getElementById("goal-nav-prev"),
            intro: "Goal Navigation: previous goal"
        },
        {
            element: document.getElementById("goal-nav-next"),
            intro: "Goal Navigation: next goal"
        },
        {
            element: document.getElementById("cd-breadcrumb"),
            intro: "Secondary navigation that reveals your current location"
        },
        {
            element: document.getElementById("tabPanel"),
            intro: "Summarised statistics about goal: total targets, indicator with data and equivalent percentage (%)"
        },
        {
            element: document.getElementById("goal-targets"),
            intro: "Goal target: Target number, icon and name"
        }
    ]
});

// A tour of the target page
var intro_target = introJs().setOptions({
    steps: [
        {
            element: document.getElementById("target-title"),
            intro: "The target numnber and name"
        },
        {
            element: document.getElementById("target-description"),
            intro: "Description of the target."  
        }
    ]
});

// A tour of the indicator page
var intro_indicator = introJs().setOptions({
    steps: [
        {
            element: document.getElementById("change-indicator"),
            intro: "Use this option to change the indicator for the goal to view it's charts, table and maps"
        }
    ]
});

/**
 * remove a step from the list of intro steps
 * @param {array} introArray array of Introjs steps
 * @param {string} stepElementId id of the step to be removed
 */
function removeIntroStep(introArray, stepElementId) {
    // removes step that have an element id which is not 'NULL'
    if((stepElementId) !== null && (stepElementId.id !== null)){
        for (var i = introArray.length - 1; i > -1; i--) {
            if (introArray[i].element.id === stepElementId) {
                introArray.splice(i, 1);
            }
        }
        
    }
    else{
        // Removes steps with element Id of 'NULL'
        for (var i = introArray.length - 1; i > -1; i--) {
            console.log('introArray[i]',introArray[i] );
//            console.log('stepElementId',stepElementId.id);
            if (introArray[i].element === stepElementId) {
                introArray.splice(i, 1);
            }
        }
    }
}
