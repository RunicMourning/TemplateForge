document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    // Cache frequently used DOM elements
    const content = document.getElementById('content');  // Renamed from blogText to content
    const preview = document.getElementById('preview');
    const linkModalEl = document.getElementById('linkModal');
    const imageModalEl = document.getElementById('imageModal');
    const colorPickerOverlay = document.getElementById('colorPickerOverlay');
    const btnColorPicker = document.querySelector('.btn-color-picker');
    
    // Initialize Bootstrap modals if available
    const linkModal = linkModalEl ? new bootstrap.Modal(linkModalEl) : null;
    const imageModal = imageModalEl ? new bootstrap.Modal(imageModalEl) : null;

    // Utility function: Update preview in real time
    const updatePreview = () => {
        preview.innerHTML = content.value;  // Update reference to content
    };

    // Utility function: Insert given text at the current cursor position
    function insertAtCursor(text) {
        const { selectionStart, selectionEnd, scrollTop } = content;  // Update reference to content
        content.value = content.value.substring(0, selectionStart) + text + content.value.substring(selectionEnd);
        content.selectionStart = selectionStart + text.length;
        content.selectionEnd = content.selectionStart;
        content.focus();
        content.scrollTop = scrollTop;
        updatePreview();
    }

    // Utility function: Return a template based on tag name
    function getTemplate(tagName) {
        const templates = {
            link: '<a href="" target="_blank">Link Text</a>',
            bold: '<strong>Bold Text</strong>',
            italic: '<em>Italic Text</em>',
            underline: '<u>Underlined Text</u>',
            strikethrough: '<del>Strikethrough Text</del>',
            unorderedList: '<ul>\n  <li>List Item 1</li>\n  <li>List Item 2</li>\n</ul>',
            orderedList: '<ol>\n  <li>Item 1</li>\n  <li>Item 2</li>\n</ol>',
            leftAlign: '<div style="text-align: left;">Aligned Left</div>',
            centerAlign: '<div style="text-align: center;">Centered Text</div>',
            rightAlign: '<div style="text-align: right;">Aligned Right</div>',
            subscript: '<sub>Subscript</sub>',
            superscript: '<sup>Superscript</sup>',
            blockquote: '<blockquote>Quoted Text</blockquote>'
        };
        return templates[tagName] || '';
    }

    // Expose functions to the global scope so inline HTML handlers can access them

    // Generic template insertion for formatting (e.g., bold, italic)
    window.insertTemplate = (tagName) => {
        let template = getTemplate(tagName);
        const { selectionStart, selectionEnd } = content;  // Update reference to content
        if (tagName === 'blockquote') {
            const selectedText = content.value.substring(selectionStart, selectionEnd) || 'Quoted Text';
            template = `<blockquote>${selectedText}</blockquote>`;
        }
        insertAtCursor(template);
    };

    // Color picker functions
    window.showColorPicker = () => {
        if (!btnColorPicker) return;
        const buttonRect = btnColorPicker.getBoundingClientRect();
        colorPickerOverlay.style.display = "block";
        colorPickerOverlay.style.top = (buttonRect.bottom + 5) + "px";
        colorPickerOverlay.style.left = buttonRect.left + "px";
    };

    window.hideColorPicker = () => {
        colorPickerOverlay.style.display = "none";
    };

    window.applyTextColor = () => {
        const colorPicker = document.getElementById('colorPicker');
        const { selectionStart, selectionEnd } = content;  // Update reference to content
        if (selectionStart === selectionEnd) {
            alert("Please select text to apply color.");
            return;
        }
        const selectedText = content.value.substring(selectionStart, selectionEnd);
        const coloredText = `<span style="color: ${colorPicker.value};">${selectedText}</span>`;
        content.value = content.value.substring(0, selectionStart) + coloredText + content.value.substring(selectionEnd);
        window.hideColorPicker();
        updatePreview();
    };

    // Modal functions for inserting link and image
    window.insertLink = () => {
        if (linkModal) {
            linkModal.show();
        }
    };

    window.insertImage = () => {
        if (imageModal) {
            imageModal.show();
        }
    };

    window.applyLink = () => {
        const linkText = document.getElementById('linkText').value.trim();
        const linkURL = document.getElementById('linkURL').value.trim();
        if (!linkText || !linkURL) {
            alert('Please enter both text and URL.');
            return;
        }
        insertAtCursor(`<a href="${linkURL}" target="_blank">${linkText}</a>`);
        linkModal.hide();
    };

    window.applyImage = () => {
        const imageURL = document.getElementById('imageURL').value.trim();
        if (!imageURL) {
            alert('Please enter an image URL.');
            return;
        }
        insertAtCursor(`<img src="${imageURL}" alt="Inserted Image">`);
        imageModal.hide();
    };

    // Update the preview as the user types
    content.addEventListener('input', updatePreview);  // Update reference to content
});
