document.addEventListener("DOMContentLoaded", function () {
    // Retrieve the selected area from localStorage, or default to 'Front Admin' if not set
    var savedArea = localStorage.getItem('selectedArea') || 'Front Admin';
    
    // Display the saved area's slots on page load
    filterByArea(savedArea);

    // Set event listeners for area buttons
    document.getElementById('btnFrontAdmin').addEventListener('click', function () {
        filterByArea('Front Admin');
    });
    document.getElementById('btnBesideCME').addEventListener('click', function () {
        filterByArea('Beside CME');
    });
    document.getElementById('btnKadasig').addEventListener('click', function () {
        filterByArea('Kadasig');
    });
    document.getElementById('btnBehind').addEventListener('click', function () {
        filterByArea('Behind');
    });

    // Event listener for slot number input to capitalize letters
    document.getElementById('slotNumberInput').addEventListener('input', function () {
        this.value = this.value.toUpperCase(); // Capitalize the input
    });

    // Event listener for form submission to validate slot number
    document.querySelector('form').addEventListener('submit', function (e) {
        var area = document.getElementById('areaSelect').value;
        var slotNumber = document.getElementById('slotNumberInput').value;

        // Determine the prefix based on the selected area
        var expectedPrefix = getAreaPrefix(area);

        // Validate that the slot number starts with the correct prefix
        if (slotNumber && (!slotNumber.startsWith(expectedPrefix) || !/^\w\d+$/.test(slotNumber))) {
            alert('Invalid slot number! Slot number should start with ' + expectedPrefix + ' and be followed by numbers (e.g., ' + expectedPrefix + '1).');
            e.preventDefault(); // Prevent form submission
        }
    });
});

// Search for a slot by slot number
function searchSlot() {
    var input = document.getElementById('searchInput').value.toLowerCase();
    var slots = document.getElementsByClassName('slot');

    for (var i = 0; i < slots.length; i++) {
        var slot = slots[i];
        if (slot.textContent.toLowerCase().includes(input)) {
            slot.style.display = '';
        } else {
            slot.style.display = 'none';
        }
    }
}

// Handle updating slot status dynamically
function updateSlotStatus(slotNumber, status) {
    var formData = new FormData();
    formData.append('slotNumber', slotNumber);
    formData.append('status', status);
    formData.append('action', 'updateStatus');

    fetch('monitor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        alert('Slot updated: ' + result);

        // Update the status dynamically without reloading the page
        var slot = document.querySelector(`div[data-slot-number="${slotNumber}"]`);
        slot.dataset.status = status; // Update the data attribute
        if (status === 'Vacant') {
            slot.classList.remove('occupied');
            slot.classList.add('vacant');
        } else {
            slot.classList.remove('vacant');
            slot.classList.add('occupied');
        }
    });
}

// Handle deleting a slot dynamically
function deleteSlot(slotNumber) {
    if (confirm("Are you sure you want to delete this slot?")) {
        var formData = new FormData();
        formData.append('slotNumber', slotNumber);
        formData.append('action', 'deleteSlot');

        fetch('monitor.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            alert('Slot deleted: ' + result);

            // Remove the slot from the DOM dynamically without reloading the page
            var slot = document.querySelector(`div[data-slot-number="${slotNumber}"]`);
            if (slot) {
                slot.remove();
            }
        });
    }
}

// Filter slots by area and save the selected area to localStorage
function filterByArea(area) {
    // Map area names to corresponding class names for filtering
    var areaClasses = {
        'Front Admin': 'front-admin',
        'Beside CME': 'beside-cme',
        'Kadasig': 'kadasig',
        'Behind': 'behind'
    };

    // Save the selected area to localStorage
    localStorage.setItem('selectedArea', area);

    // Get all slots
    var allSlots = document.getElementsByClassName('slot');

    // Loop through all slots and show/hide based on the selected area
    for (var i = 0; i < allSlots.length; i++) {
        var slot = allSlots[i];
        slot.style.display = 'none'; // Hide all slots initially

        // Show slots that match the selected area's class
        if (slot.classList.contains(areaClasses[area])) {
            slot.style.display = 'block';
        }
    }
}

// Toggle the display of buttons for the clicked slot
function toggleSlotButtons(slotNumber) {
    // Hide all other slot actions first
    var allSlots = document.getElementsByClassName('slot-actions');
    for (var i = 0; i < allSlots.length; i++) {
        allSlots[i].style.display = 'none';
    }

    // Now show the actions for the clicked slot
    var slotActions = document.getElementById('slotActions-' + slotNumber);
    if (slotActions.style.display === 'none' || slotActions.style.display === '') {
        slotActions.style.display = 'block';
    } else {
        slotActions.style.display = 'none';
    }
}

// Helper function to get the prefix based on the selected area
function getAreaPrefix(area) {
    switch (area) {
        case 'Front Admin':
            return 'A';
        case 'Beside CME':
            return 'B';
        case 'Kadasig':
            return 'C';
        case 'Behind':
            return 'D';
        default:
            return '';
    }
}
