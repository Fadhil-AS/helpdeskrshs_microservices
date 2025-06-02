let _validBuktiPendukungFiles = [];
let _buktiPendukungFileInput, _buktiPendukungDropAreaLabel, _uploadBoxContent, _buktiErrorContainer, _originalUploadBoxHTML;

function initBuktiPendukungUI(
    buktiFileInput,
    dropAreaLabel,
    uploadContent,
    errorContainer,
    originalHTML,
    filesArrayReference
) {
    _buktiPendukungFileInput = buktiFileInput;
    _buktiPendukungDropAreaLabel = dropAreaLabel;
    _uploadBoxContent = uploadContent;
    _buktiErrorContainer = errorContainer;
    _originalUploadBoxHTML = originalHTML;

    _validBuktiPendukungFiles = filesArrayReference;

    console.log("initBuktiPendukungUI: _validBuktiPendukungFiles diinisialisasi dengan:", _validBuktiPendukungFiles, Array.isArray(_validBuktiPendukungFiles));


    if (_buktiPendukungFileInput && _buktiPendukungDropAreaLabel && _uploadBoxContent) {
        renderBuktiPendukungUI();
        _buktiPendukungFileInput.addEventListener('change', (event) => processNewBuktiPendukungFiles(event.target.files));

        function preventDefaults(e) { e.preventDefault(); e.stopPropagation(); }

        if (_buktiPendukungDropAreaLabel) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                _buktiPendukungDropAreaLabel.addEventListener(eventName, preventDefaults, false);
                if (eventName === 'dragover' || eventName === 'drop') {
                     document.body.addEventListener(eventName, preventDefaults, false);
                }
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                _buktiPendukungDropAreaLabel.addEventListener(eventName, () => {
                    if(_buktiPendukungDropAreaLabel) _buktiPendukungDropAreaLabel.classList.add('highlight');
                }, false);
            });
            ['dragleave', 'drop'].forEach(eventName => {
                _buktiPendukungDropAreaLabel.addEventListener(eventName, () => {
                    if(_buktiPendukungDropAreaLabel) _buktiPendukungDropAreaLabel.classList.remove('highlight');
                }, false);
            });
            _buktiPendukungDropAreaLabel.addEventListener('drop', function(event) {
                const dt = event.dataTransfer;
                processNewBuktiPendukungFiles(dt.files);
            }, false);
        } else {
            console.error("initBuktiPendukungUI: _buktiPendukungDropAreaLabel (target drop zone) null, event listener TIDAK DIPASANG.");
        }
    } else {
         console.warn("Satu atau lebih elemen UI untuk Bukti Pendukung tidak ditemukan saat initBuktiPendukungUI.");
    }
}

function renderBuktiPendukungUI() {
    if (!_uploadBoxContent) {
        console.warn("renderBuktiPendukungUI: _uploadBoxContent tidak ada.");
        return;
    }
    _uploadBoxContent.innerHTML = '';

    if (!Array.isArray(_validBuktiPendukungFiles)) {
        console.error("renderBuktiPendukungUI: _validBuktiPendukungFiles BUKAN array!", _validBuktiPendukungFiles);
        _validBuktiPendukungFiles = [];
    }

    if (_validBuktiPendukungFiles.length === 0) {
        _uploadBoxContent.innerHTML = _originalUploadBoxHTML;
        if (_buktiPendukungDropAreaLabel) _buktiPendukungDropAreaLabel.classList.remove('has-files');
        _uploadBoxContent.style.justifyContent = 'center';
        _uploadBoxContent.style.alignItems = 'center';
    } else {
        if (_buktiPendukungDropAreaLabel) _buktiPendukungDropAreaLabel.classList.add('has-files');
        _uploadBoxContent.style.justifyContent = 'flex-start';
        _uploadBoxContent.style.alignItems = 'stretch';

        const filesGridContainer = document.createElement('div');
        filesGridContainer.id = 'fileGridContainer';
        filesGridContainer.classList.add('d-flex', 'flex-wrap', 'justify-content-start', 'align-items-stretch', 'gap-2');

        _validBuktiPendukungFiles.forEach((file, index) => {
            const fileBox = document.createElement('div');
            fileBox.classList.add('file-item-box');

            const previewSection = document.createElement('div');
            previewSection.classList.add('file-preview-section');
            if (file.type.startsWith('image/')) {
                const imgPreview = document.createElement('img');
                const objectUrl = URL.createObjectURL(file);
                imgPreview.src = objectUrl;
                imgPreview.alt = file.name;
                imgPreview.onload = () => URL.revokeObjectURL(objectUrl);
                previewSection.appendChild(imgPreview);
            } else {
                const fileIconContainer = document.createElement('div');
                fileIconContainer.classList.add('file-icon', 'text-muted', 'd-flex', 'justify-content-center', 'align-items-center', 'h-100');
                let iconClass = 'bi-file-earmark-text';
                if (file.type === 'application/pdf') iconClass = 'bi-file-earmark-pdf text-danger';
                fileIconContainer.innerHTML = `<i class="bi ${iconClass}" style="font-size: 2rem;"></i>`;
                previewSection.appendChild(fileIconContainer);
            }
            fileBox.appendChild(previewSection);

            const nameAndSizeSection = document.createElement('div');
            nameAndSizeSection.classList.add('file-details-section');
            const fileNameDiv = document.createElement('div');
            fileNameDiv.classList.add('file-name');
            fileNameDiv.textContent = file.name.length > 20 ? file.name.substring(0, 17) + '...' : file.name;
            fileNameDiv.title = file.name;
            nameAndSizeSection.appendChild(fileNameDiv);
            const fileSizeDiv = document.createElement('div');
            fileSizeDiv.classList.add('file-size', 'text-muted');
            fileSizeDiv.textContent = `(${(file.size / (1024 * 1024)).toFixed(2)} MB)`;
            nameAndSizeSection.appendChild(fileSizeDiv);
            fileBox.appendChild(nameAndSizeSection);

            const removeButtonSection = document.createElement('div');
            removeButtonSection.classList.add('file-remove-section');
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.classList.add('btn', 'btn-sm', 'btn-danger', 'btn-remove-file');
            removeBtn.innerHTML = '<i class="bi bi-trash"></i>';
            removeBtn.setAttribute('aria-label', `Hapus file ${file.name}`);
            removeBtn.onclick = function(event) {
                event.stopPropagation();
                event.preventDefault();
                removeBuktiPendukungFile(index);
            };
            removeButtonSection.appendChild(removeBtn);
            fileBox.appendChild(removeButtonSection);

            filesGridContainer.appendChild(fileBox);
        });
        _uploadBoxContent.appendChild(filesGridContainer);

        if (_validBuktiPendukungFiles.length > 0) {
             const addMorePrompt = document.createElement('div');
             addMorePrompt.classList.add('add-more-prompt', 'text-center', 'w-100', 'p-3', 'border-top', 'mt-2');
             addMorePrompt.innerHTML = `<small class="text-muted">Klik area ini lagi atau drag & drop untuk menambah file lain.</small>`;
             _uploadBoxContent.appendChild(addMorePrompt);
        }
    }
    if (_buktiPendukungFileInput) _buktiPendukungFileInput.value = '';
}

function removeBuktiPendukungFile(indexToRemove) {
    if (!Array.isArray(_validBuktiPendukungFiles)) {
        console.error("removeBuktiPendukungFile: _validBuktiPendukungFiles BUKAN array!");
        return;
    }
    if (indexToRemove < 0 || indexToRemove >= _validBuktiPendukungFiles.length) return;
    _validBuktiPendukungFiles.splice(indexToRemove, 1);
    renderBuktiPendukungUI();
    if (_buktiErrorContainer) _buktiErrorContainer.innerHTML = '';
}

function processNewBuktiPendukungFiles(newlySelectedFiles) {
    if (_buktiErrorContainer) _buktiErrorContainer.innerHTML = '';
    let filesActuallyAdded = 0;

    if (!Array.isArray(_validBuktiPendukungFiles)) {
        console.error("processNewBuktiPendukungFiles: _validBuktiPendukungFiles BUKAN array! Membuat array baru.");
        _validBuktiPendukungFiles = [];
    }

    for (const file of newlySelectedFiles) {
        const validationResult = validateFileGlobal(file);
        if (validationResult.valid) {
            const isDuplicate = _validBuktiPendukungFiles.some(
                existingFile => existingFile.name === file.name &&
                existingFile.size === file.size &&
                existingFile.lastModified === file.lastModified
            );
            if (!isDuplicate) {
                _validBuktiPendukungFiles.push(file);
                filesActuallyAdded++;
            } else {
                if (_buktiErrorContainer) _buktiErrorContainer.innerHTML += `<p class="mb-1 text-warning">File ${file.name} sudah ada.</p>`;
            }
        } else {
            if (_buktiErrorContainer) _buktiErrorContainer.innerHTML += `<p class="mb-1 text-danger">${validationResult.message}</p>`;
        }
    }
    if (filesActuallyAdded > 0) {
        renderBuktiPendukungUI();
    }
}
