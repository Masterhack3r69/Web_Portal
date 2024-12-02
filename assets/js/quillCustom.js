// Quill Editor Configuration
document.addEventListener('DOMContentLoaded', function() {
    // Default Quill options
    const defaultOptions = {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                [{ 'align': [] }],
                ['clean']
            ]
        },
        placeholder: 'Start typing here...',
    };

    // Function to initialize a single Quill instance
    function initializeQuill(editorId, hiddenInputId, customPlaceholder = null) {
        const editorElement = document.getElementById(editorId);
        if (!editorElement) return null;

        const options = {...defaultOptions};
        if (customPlaceholder) {
            options.placeholder = customPlaceholder;
        }

        const quill = new Quill('#' + editorId, options);
        
        if (hiddenInputId) {
            const hiddenInput = document.getElementById(hiddenInputId);
            if (hiddenInput) {
                // Update hidden input when text changes
                quill.on('text-change', function() {
                    hiddenInput.value = quill.root.innerHTML;
                });

                // If hidden input has initial value, set it in editor
                if (hiddenInput.value) {
                    quill.root.innerHTML = hiddenInput.value;
                }
            }
        }

        return quill;
    }

    // All editor configurations
    const editorConfigs = {
        // Content editor
        content: {
            editor: 'editor-content',
            hidden: 'content',
            placeholder: 'Enter the content here...'
        },
        
        // Department editors
        localDepartment: {
            editor: 'editor-local-department-description',
            hidden: 'localDepartmentDescription',
            placeholder: 'Enter description here...'
        },
        editDepartment: {
            editor: 'editor-edit-department-description',
            hidden: 'editDepartmentDescription',
            placeholder: 'Enter description here...'
        },
        
        // Program editors
        programDescription: {
            editor: 'editor-description',
            hidden: 'programDescription',
            placeholder: 'Enter the program description...'
        },
        programGuidelines: {
            editor: 'editor-guidelines',
            hidden: 'programGuidelines',
            placeholder: 'Enter the program guidelines...'
        },
        programRequirements: {
            editor: 'editor-requirements',
            hidden: 'programRequirements',
            placeholder: 'Enter the program requirements...'
        },
        editProgramDescription: {
            editor: 'editor-edit-description',
            hidden: 'editProgramDescription',
            placeholder: 'Enter the program description...'
        },
        editProgramGuidelines: {
            editor: 'editor-edit-guidelines',
            hidden: 'editProgramGuidelines',
            placeholder: 'Enter the program guidelines...'
        },
        editProgramRequirements: {
            editor: 'editor-edit-requirements',
            hidden: 'editProgramRequirements',
            placeholder: 'Enter the program requirements...'
        },
        
        // Enquiry editors
        enquiryDescription: {
            editor: 'editor-enquiry-description',
            hidden: 'enquiryDescription',
            placeholder: 'Enter enquiry description...'
        },
        editEnquiryDescription: {
            editor: 'editor-edit-enquiry-description',
            hidden: 'editEnquiryDescription',
            placeholder: 'Enter enquiry description...'
        }
    };

    // Initialize all editors
    const initializedEditors = {};
    Object.entries(editorConfigs).forEach(([key, config]) => {
        const editor = initializeQuill(config.editor, config.hidden, config.placeholder);
        if (editor) {
            initializedEditors[key] = editor;
        }
    });

    // Handle form submission for all editors
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            Object.entries(editorConfigs).forEach(([key, config]) => {
                const hiddenInput = document.getElementById(config.hidden);
                const editor = initializedEditors[key];
                if (hiddenInput && editor) {
                    hiddenInput.value = editor.root.innerHTML;
                }
            });
        });
    });
});