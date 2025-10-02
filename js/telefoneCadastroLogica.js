document.addEventListener('DOMContentLoaded', () => {
    const telefoneInput = document.getElementById('telefone');
    
    const prefixo = '+55 (14) ';
    const prefixoLength = prefixo.length;
    const maxDigits = 9;

    function formatNumber(digits) {
        if (digits.length <= 5) {
            return digits;
        }
        return digits.slice(0, 5) + '-' + digits.slice(5, maxDigits);
    }

    function updateField(digits) {
        const formatted = formatNumber(digits);
        telefoneInput.value = prefixo + formatted;
        
        const cursorPosition = prefixoLength + digits.length + (digits.length > 5 ? 1 : 0);
        telefoneInput.setSelectionRange(cursorPosition, cursorPosition);
    }

    telefoneInput.addEventListener('focus', () => {
        if (!telefoneInput.value.startsWith(prefixo)) {
            telefoneInput.value = prefixo;
        }
    });

    telefoneInput.addEventListener('input', (e) => {
        const cleanInput = e.target.value.replace(/\D/g, '');
        const digits = cleanInput.slice(4, 4 + maxDigits);
        
        updateField(digits);
    });

    telefoneInput.addEventListener('keydown', (e) => {
        const key = e.key;
        const selectionStart = telefoneInput.selectionStart;

        // Impede que o usuário apague ou altere o prefixo
        if (selectionStart < prefixoLength && (key === 'Backspace' || key === 'Delete')) {
            e.preventDefault();
        }
    });

    telefoneInput.addEventListener('blur', () => {
        const digits = telefoneInput.value.replace(/\D/g, '');
        if (digits.length <= 4) {
            telefoneInput.value = '';
        }
    });
});