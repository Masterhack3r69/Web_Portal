let fieldCount = 0;

document.addEventListener('DOMContentLoaded', () => {
    // Initialize existing fields
    document.querySelectorAll('#form-fields .mb-3').forEach((field, index) => {
        const id = field.getAttribute('id').split('-')[1];
        const label = field.querySelector('label').innerText || `Field ${index + 1}`;
        addToFieldList(label, id);
    });

    // Enable move buttons for loaded fields
    toggleMoveButtons();
});

function toggleOptionsInput() {
    const fieldType = document.getElementById('field-type').value;
    const optionsContainer = document.getElementById('options-input-container');
    const inlineContainer = document.getElementById('inline-option-container');
    const textviewContainer = document.getElementById('textview-input-container'); // Add this line

    // Reset all containers
    optionsContainer.style.display = 'none';
    inlineContainer.style.display = 'none';
    textviewContainer.style.display = 'none'; // Add this line

    if (['select', 'radio', 'checkbox'].includes(fieldType)) {
        optionsContainer.style.display = 'block';
        inlineContainer.style.display = fieldType === 'select' ? 'none' : 'block'; 
    } else if (fieldType === 'header') { 
        textviewContainer.style.display = 'block';
    }
}


function addField() {
    const fieldType = document.getElementById('field-type').value;
    const fieldLabel = document.getElementById('field-label').value;
    const fieldRequired = document.getElementById('field-required').checked;
    const fieldOptions = document.getElementById('field-options').value;
    const fieldInline = document.getElementById('field-inline').checked;
    const fieldTextviewContent = document.getElementById('field-textview-content').value;
    const formFields = document.getElementById('form-fields');
    
    if (!fieldType || !fieldLabel) {
        alert('Please select a field type and enter a label');
        return;
    }

    // Remove existing terms and submit section if it exists
    const existingTermsSection = document.querySelector('#terms-submit-section');
    if (existingTermsSection) {
        existingTermsSection.remove();
    }

    fieldCount++;
    let newField;
    let requiredAttr = fieldRequired ? 'required' : '';
    let requiredStar = fieldRequired ? '<span class="text-danger">*</span>' : '';
    let inlineClass = fieldInline ? 'form-check-inline' : '';

    // Convert fieldLabel to a standardized field name
    const fieldName = fieldLabel.toLowerCase()
        .replace(/first name/i, 'first_name')
        .replace(/last name/i, 'last_name')
        .replace(/middle name/i, 'middle_name')
        .replace(/email/i, 'email')
        .replace(/phone/i, 'phone')
        .replace(/\s+/g, '_');

    switch (fieldType) {
        case 'text':
            newField = `<div class="mb-3 col-12" id="field-${fieldCount}">
                            <label class="form-check-label">${fieldLabel}: ${requiredStar}</label>
                            <input type="text" name="${fieldName}" class="form-control" placeholder="Enter ${fieldLabel}" ${requiredAttr}>
                        </div>`;
            break;
        case 'textarea':
            newField = `<div class="mb-3 col-12" id="field-${fieldCount}">
                            <label class="form-check-label">${fieldLabel}: ${requiredStar}</label>
                            <textarea name="${fieldName}" class="form-control" rows="3" placeholder="Enter ${fieldLabel}" ${requiredAttr}></textarea>
                        </div>`;
            break;
        case 'email':
            newField = `<div class="mb-3 col-12" id="field-${fieldCount}">
                            <label class="form-check-label">${fieldLabel}: ${requiredStar}</label>
                            <input type="email" name="${fieldName}" class="form-control" placeholder="Enter ${fieldLabel}" ${requiredAttr}>
                        </div>`;
            break;
        case 'number':  
            newField = `<div class="mb-3 col-12" id="field-${fieldCount}">
                            <label class="form-check-label">${fieldLabel}: ${requiredStar}</label>
                            <input type="number" name="${fieldName}" class="form-control" placeholder="Enter ${fieldLabel}" ${requiredAttr}>
                        </div>`;
            break;
        case 'radio':
            const radioOptions = fieldOptions.split(',').map(option => 
                `<div class="form-check ${inlineClass} me-3">
                    <input type="radio" name="${fieldName}" class="form-check-input" value="${option.trim()}" ${requiredAttr}>
                    <label class="form-check-label">${option.trim()}</label>
                </div>`).join('');
            newField = `<div class="mb-3 col-12" id="field-${fieldCount}">
                            <label class="form-check-label d-block">${fieldLabel}: ${requiredStar}</label>
                            <div class="${inlineClass ? 'd-inline-flex' : ''}">
                                ${radioOptions}
                            </div>
                        </div>`;
            break;
        case 'select':
            const selectOptions = fieldOptions.split(',').map(option => `<option>${option.trim()}</option>`).join('');
            newField = `<div class="mb-3 col-12" id="field-${fieldCount}">
                            <label class="form-check-label">${fieldLabel}: ${requiredStar}</label>
                            <select name="${fieldName}" class="form-select" ${requiredAttr}>
                                ${selectOptions}
                            </select>
                        </div>`;
            break;
        case 'checkbox':    
            const checkboxOptions = fieldOptions.split(',').map(option => 
                `<div class="form-check ${inlineClass} me-3">
                    <input type="checkbox" name="${fieldName}[]" value="${option.trim()}" class="form-check-input" ${requiredAttr}>
                    <label class="form-check-label">${option.trim()}</label>
                </div>`).join('');
            newField = `<div class="mb-3 col-12" id="field-${fieldCount}">
                            <label class="form-check-label d-block">${fieldLabel}: ${requiredStar}</label>
                            <div class="${inlineClass ? 'd-inline-flex' : ''}">
                                ${checkboxOptions}
                            </div>
                        </div>`;
            break;
        case 'date':
            newField = `<div class="mb-3 col-12" id="field-${fieldCount}">
                            <label class="form-check-label">${fieldLabel}: ${requiredStar}</label>
                            <input type="date" name="${fieldName}" class="form-control" ${requiredAttr}>
                        </div>`;
            break;
        case 'file':
            newField = `<div class="mb-3 col-12" id="field-${fieldCount}">
                            <label class="form-check-label">${fieldLabel}: ${requiredStar}</label>
                            <input type="file" name="${fieldName}" class="form-control" ${requiredAttr}>
                        </div>`;
            break;
        case 'header':
            newField = `<div class="col-12" id="field-${fieldCount}">
                            <p>${fieldTextviewContent}</p>
                        </div>`;
            break;
        default:
            newField = '';
    }

    if (newField) {
        formFields.insertAdjacentHTML('beforeend', newField);
        
        // Add terms and submit section
        const termsSubmitSection = `
            <div id="terms-submit-section" class="px-4">
                <div class="form-check my-1">
                    <input class="form-check-input" type="checkbox" id="modalAcknowledgment" required>
                    <label class="form-check-label" for="modalAcknowledgment">I have read and understand the <a type="button" class="link-danger" data-bs-toggle="modal" data-bs-target="#consentModal">Terms and Conditions</a></label>
                </div>
                <button id="submitBtn" type="button" class="btn border-0 btn-lg w-100 mb-4" onclick="validateForm()" data-toggle="tooltip" title="Submit Form">Submit</button>
            </div>`;
        formFields.insertAdjacentHTML('beforeend', termsSubmitSection);
        
        addToFieldList(fieldLabel, fieldCount);
        clearFieldInputs();
        toggleMoveButtons();
    }
}

function addToFieldList(label, id) {
    const fieldList = document.getElementById('field-list');
    const listItem = `<li class="list-group-item d-flex justify-content-between align-items-center" id="list-item-${id}">
                       <span style="font-size: 0.775rem;">${label}</span>
                        <div>
                            <button class="btn bg-gradient-secondary px-2 py-1 m-0" onclick="moveFieldUp(${id})" title="Move Up">
                                <i class="fa fa-arrow-up"></i>
                            </button>
                            <button class="btn bg-gradient-secondary px-2 py-1 m-0" onclick="moveFieldDown(${id})" title="Move Down">
                                <i class="fa fa-arrow-down"></i>
                            </button>
                            <button class="btn bg-gradient-danger px-2 py-1 m-0" onclick="removeField(${id})" title="Delete">
                                <i class="fa fa-times text-white"></i>
                            </button>
                        </div>
                        </li>`;
    fieldList.insertAdjacentHTML('beforeend', listItem);
}

function moveFieldUp(id) {
    const listItem = document.getElementById(`list-item-${id}`);
    const fieldItem = document.getElementById(`field-${id}`);
    if (listItem.previousElementSibling) {
        listItem.parentNode.insertBefore(listItem, listItem.previousElementSibling);
        fieldItem.parentNode.insertBefore(fieldItem, fieldItem.previousElementSibling);
    }
    toggleMoveButtons();
}

function moveFieldDown(id) {
    const listItem = document.getElementById(`list-item-${id}`);
    const fieldItem = document.getElementById(`field-${id}`);
    if (listItem.nextElementSibling) {
        listItem.parentNode.insertBefore(listItem.nextElementSibling, listItem);
        fieldItem.parentNode.insertBefore(fieldItem.nextElementSibling, fieldItem);
    }
    toggleMoveButtons();
}

function toggleMoveButtons() {
    const fieldListItems = document.querySelectorAll('#field-list li');
    fieldListItems.forEach((item, index) => {
        const upButton = item.querySelector('.fa-arrow-up').parentElement;
        const downButton = item.querySelector('.fa-arrow-down').parentElement;
        upButton.disabled = index === 0;
        downButton.disabled = index === fieldListItems.length - 1;
    });
}

function removeField(id) {
    document.getElementById(`field-${id}`).remove();
    document.getElementById(`list-item-${id}`).remove();
    toggleMoveButtons();
}

function clearFieldInputs() {
    document.getElementById('field-label').value = '';
    document.getElementById('field-type').value = '';
    document.getElementById('field-required').checked = false;
    document.getElementById('field-options').value = '';
    document.getElementById('field-inline').checked = false;
    document.getElementById('options-input-container').style.display = 'none';
    document.getElementById('inline-option-container').style.display = 'none';
    document.getElementById('textview-input-container').style.display = 'none';
}

function validateForm() {
    const formFields = document.getElementById('form-fields');
    const requiredFields = formFields.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalidField = null;

    requiredFields.forEach(field => {
        // Reset previous validation styling
        field.classList.remove('is-invalid');
        
        // Check if field is empty or unchecked (for checkboxes and radio buttons)
        if (field.type === 'checkbox' || field.type === 'radio') {
            // For radio buttons, check if any in the group is selected
            if (field.type === 'radio') {
                const radioGroup = formFields.querySelectorAll(`input[name="${field.name}"]`);
                const isChecked = Array.from(radioGroup).some(radio => radio.checked);
                if (!isChecked) {
                    isValid = false;
                    field.closest('.mb-3').querySelectorAll('input[type="radio"]')
                        .forEach(radio => radio.classList.add('is-invalid'));
                    if (!firstInvalidField) firstInvalidField = field;
                }
            } else if (!field.checked) {
                isValid = false;
                field.classList.add('is-invalid');
                if (!firstInvalidField) firstInvalidField = field;
            }
        } else if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
            if (!firstInvalidField) firstInvalidField = field;
        }
    });

    if (!isValid && firstInvalidField) {
        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstInvalidField.focus();
    }

    return isValid;
}
