(function () {
    const config = window.workflowTemplateFormConfig || {};
    const servicesUrlTemplate = config.servicesUrlTemplate;
    const excludeTemplateId = config.excludeTemplateId || null;
    const initialStages = config.initialStages || [];

    const industrySelect = document.getElementById("industry_id");
    const nameInput = document.getElementById("name");
    const stagesContainer = document.getElementById("stagesContainer");
    const addStageBtn = document.getElementById("addStageBtn");
    const industryError = document.getElementById("industryError");
    const saveBtn = document.getElementById("saveWorkflowTemplateBtn");
    const form = document.getElementById("workflowTemplateForm");

    let stageIndex = 0;
    let industryServices = []; // [{id, name, used}]
    let servicesLoaded = false;

    function fetchServices(industryId) {
        const url =
            servicesUrlTemplate.replace("__INDUSTRY_ID__", industryId) +
            (excludeTemplateId
                ? "?exclude_template_id=" + excludeTemplateId
                : "");

        return fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
            .then((res) => res.json())
            .then((data) => {
                industryServices = data;
                servicesLoaded = true;
                return data;
            });
    }

    function currentlySelectedServiceIds(exceptRow) {
        const selected = [];
        stagesContainer.querySelectorAll(".stage-row").forEach((row) => {
            if (row === exceptRow) return;
            const val = row.querySelector(".stage-service-select").value;
            if (val) selected.push(parseInt(val, 10));
        });
        return selected;
    }

    function populateServiceSelect(select, currentValue) {
        const selectedElsewhere = currentlySelectedServiceIds(
            select.closest(".stage-row"),
        );

        select.innerHTML =
            '<option value="" selected disabled>Select Service</option>';

        industryServices.forEach((service) => {
            const option = document.createElement("option");
            option.value = service.id;
            option.textContent = service.name;

            const isCurrent = String(service.id) === String(currentValue);
            const usedGlobally = service.used && !isCurrent;
            const usedInAnotherRow =
                selectedElsewhere.includes(service.id) && !isCurrent;

            if (usedGlobally || usedInAnotherRow) {
                option.disabled = true;
                option.textContent += usedGlobally
                    ? " (already used)"
                    : " (selected in another stage)";
            }

            select.appendChild(option);
        });

        if (currentValue) {
            select.value = currentValue;
        }
    }

    function refreshAllServiceSelects() {
        stagesContainer
            .querySelectorAll(".stage-service-select")
            .forEach((select) => {
                populateServiceSelect(
                    select,
                    select.dataset.value || select.value,
                );
            });
    }

    function addStageRow(stage) {
        stage = stage || {};
        const index = stageIndex++;

        const row = document.createElement("div");
        row.className = "row g-2 align-items-center stage-row mb-2";
        row.innerHTML = `
            <input type="hidden" name="stages[${index}][id]" value="${stage.id ?? ""}">
            <div class="col-5">
                <select class="form-select stage-service-select" name="stages[${index}][service_id]" required></select>
            </div>
            <div class="col-3">
                <input type="number" min="1" class="form-control stage-sequence-input"
                    name="stages[${index}][sequence_no]" placeholder="Sequence No." value="${stage.sequence_no ?? ""}" required>
            </div>
            <div class="col-3">
                <div class="form-check form-switch mt-2">
                    <input type="checkbox" class="form-check-input stage-mandatory-input"
                        name="stages[${index}][is_mandatory]" value="1" ${stage.is_mandatory === false ? "" : "checked"}>
                    <label class="form-check-label">Mandatory</label>
                </div>
            </div>
            <div class="col-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger remove-stage-btn">
                    <i data-feather="trash-2" class="feather-icon"></i>
                </button>
            </div>
        `;

        stagesContainer.appendChild(row);

        const select = row.querySelector(".stage-service-select");
        select.dataset.value = stage.service_id ?? "";
        populateServiceSelect(select, stage.service_id ?? "");

        if (typeof feather !== "undefined") feather.replace();
        validateForm();
    }

    addStageBtn.addEventListener("click", function () {
        const industryId = industrySelect.value;

        if (!industryId) {
            industryError.classList.remove("d-none");
            industrySelect.classList.add("is-invalid");
            return;
        }

        industryError.classList.add("d-none");
        industrySelect.classList.remove("is-invalid");

        if (servicesLoaded) {
            addStageRow();
        } else {
            fetchServices(industryId).then(() => addStageRow());
        }
    });

    industrySelect.addEventListener("change", function () {
        stagesContainer.innerHTML = "";
        stageIndex = 0;
        servicesLoaded = false;
        industryError.classList.add("d-none");
        industrySelect.classList.remove("is-invalid");
        validateForm();

        if (industrySelect.value) {
            fetchServices(industrySelect.value);
        }
    });

    stagesContainer.addEventListener("click", function (e) {
        const btn = e.target.closest(".remove-stage-btn");
        if (btn) {
            btn.closest(".stage-row").remove();
            refreshAllServiceSelects();
            validateForm();
        }
    });

    stagesContainer.addEventListener("change", function (e) {
        if (e.target.classList.contains("stage-service-select")) {
            e.target.dataset.value = e.target.value;
            refreshAllServiceSelects();
        }
        validateForm();
    });

    stagesContainer.addEventListener("input", validateForm);
    industrySelect.addEventListener("change", validateForm);
    nameInput.addEventListener("input", validateForm);

    function validateForm() {
        let valid = true;

        if (!industrySelect.value) valid = false;
        if (!nameInput.value.trim()) valid = false;

        const rows = stagesContainer.querySelectorAll(".stage-row");
        if (rows.length === 0) valid = false;

        rows.forEach((row) => {
            const serviceVal = row.querySelector(".stage-service-select").value;
            const seqVal = row.querySelector(".stage-sequence-input").value;
            if (!serviceVal || !seqVal) valid = false;
        });

        saveBtn.disabled = !valid;
        return valid;
    }

    document.addEventListener("DOMContentLoaded", function () {
        if (industrySelect.value) {
            fetchServices(industrySelect.value).then(() => {
                if (initialStages.length) {
                    initialStages.forEach((stage) => addStageRow(stage));
                } else {
                    addStageRow();
                }
                validateForm();
            });
        } else {
            validateForm();
        }
    });

    form.addEventListener("submit", function () {
        saveBtn.disabled = true;
        saveBtn.innerHTML = "Saving...";
    });
})();
