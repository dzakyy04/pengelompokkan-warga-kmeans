function selectPekerjaan(row, formId) {
    document.querySelectorAll('.pekerjaan-row-' + formId).forEach(function(r) {
        r.classList.remove('bg-emerald-50', 'dark:bg-emerald-900/20');
        r.querySelector('.pekerjaan-radio-' + formId).classList.remove('border-emerald-500', 'bg-emerald-500');
    });

    row.classList.add('bg-emerald-50', 'dark:bg-emerald-900/20');
    row.querySelector('.pekerjaan-radio-' + formId).classList.add('border-emerald-500', 'bg-emerald-500');

    var pekerjaan = row.dataset.pekerjaan;

    if (pekerjaan === '__lainnya__') {
        document.getElementById('panel-lainnya-' + formId).classList.remove('hidden');
        document.getElementById('status-summary-' + formId).classList.add('hidden');
        document.getElementById('input_pekerjaan_' + formId).value = '';
        document.getElementById('input_status_' + formId).value = '';
        document.getElementById('input_skor_' + formId).value = '';
    } else {
        document.getElementById('panel-lainnya-' + formId).classList.add('hidden');
        var status = row.dataset.status;
        var skor = row.dataset.skor;

        document.getElementById('input_pekerjaan_' + formId).value = pekerjaan;
        document.getElementById('input_status_' + formId).value = status;
        document.getElementById('input_skor_' + formId).value = skor;

        document.getElementById('summary-pekerjaan-' + formId).textContent = pekerjaan;
        document.getElementById('summary-status-' + formId).textContent = status;
        document.getElementById('status-summary-' + formId).classList.remove('hidden');
    }
}

function updateLainnyaPekerjaan(val, formId) {
    document.getElementById('input_pekerjaan_' + formId).value = val;
    document.getElementById('summary-pekerjaan-' + formId).textContent = val || '-';
    refreshSummaryLainnya(formId);
}

function updateLainnyaStatus(sel, formId) {
    var opt = sel.options[sel.selectedIndex];
    var status = opt.value;
    var skor = opt.dataset.skor || '';

    document.getElementById('input_status_' + formId).value = status;
    document.getElementById('input_skor_' + formId).value = skor;
    document.getElementById('summary-status-' + formId).textContent = status ? status : '-';
    refreshSummaryLainnya(formId);
}

function refreshSummaryLainnya(formId) {
    var pek = document.getElementById('input_pekerjaan_' + formId).value;
    var sts = document.getElementById('input_status_' + formId).value;
    if (pek && sts) {
        document.getElementById('status-summary-' + formId).classList.remove('hidden');
    } else {
        document.getElementById('status-summary-' + formId).classList.add('hidden');
    }
}
