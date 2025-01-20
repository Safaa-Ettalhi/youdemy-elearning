const burgerIcon = document.getElementById('burger-icon');
        const mobileMenu = document.getElementById('mobile-menu');
        const menu = document.getElementById('menu');

        burgerIcon.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
     
        function openModal(courseData) {
            
            document.getElementById('modalTitle').textContent = courseData.titre;
            document.getElementById('modalDescription').textContent = courseData.description;
            document.getElementById('modalCategory').textContent = courseData.categorie_nom;

            const tagsContainer = document.getElementById('modalTags');
            tagsContainer.innerHTML = '';
            if (courseData.tags) {
                courseData.tags.split(',').forEach(tag => {
                    const tagElement = document.createElement('span');
                    tagElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
                    tagElement.textContent = tag;
                    tagsContainer.appendChild(tagElement);
                });
            }
    

            const videoContainer = document.getElementById('modalVideo');
            if (courseData.vedio) {
                videoContainer.innerHTML = `
                   
                    <iframe class="w-full h-full rounded-lg" 
                    src="${courseData.vedio}" 
                    title="Vidéo du cours" 
                    allowfullscreen>
            </iframe>
                `;
            } else {
                videoContainer.innerHTML = '<p class="text-sm text-gray-500">Aucune vidéo disponible</p>';
            }
   
            const documentContainer = document.getElementById('modalDocument');
            if (courseData.fichier_document) {
                documentContainer.innerHTML = `
                    <a href="${courseData.fichier_document}" 
                       target="_blank" 
                       class="inline-flex items-center text-sm text-orange-600 hover:text-orange-900">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Voir le document
                    </a>
                `;
            } else {
                documentContainer.innerHTML = '<p class="text-sm text-gray-500">Aucun document disponible</p>';
            }
    
        
            document.getElementById('courseModal').classList.remove('hidden');
        }
    
       

        function toggleForm() {
            var form = document.getElementById('add');
            form.classList.toggle('hidden'); 
        }
        function closeModal() {
            document.getElementById('add').classList.add('hidden');
        }
      
          document.getElementById('content-type').addEventListener('change', function () {
            const videoUpload = document.getElementById('video-upload');
            const documentUpload = document.getElementById('document-upload');
        
           
            if (this.value === 'video') {
                videoUpload.classList.remove('hidden');
                documentUpload.classList.add('hidden');
            } 
           
            else if (this.value === 'document') {
                videoUpload.classList.add('hidden');
                documentUpload.classList.remove('hidden');
            } 
            
            else {
                videoUpload.classList.add('hidden');
                documentUpload.classList.add('hidden');
            }
        });
        
        document.addEventListener('DOMContentLoaded', function () {
            const initialType = document.getElementById('content-type').value;
            const videoUpload = document.getElementById('video-upload');
            const documentUpload = document.getElementById('document-upload');
        
            if (initialType === 'video') {
                videoUpload.classList.remove('hidden');
                documentUpload.classList.add('hidden');
            } else if (initialType === 'document') {
                videoUpload.classList.add('hidden');
                documentUpload.classList.remove('hidden');
            } else {
                videoUpload.classList.add('hidden');
                documentUpload.classList.add('hidden');
            }
        });



        const fileInput = document.getElementById('file-upload-image');
        const fileNameDiv = document.getElementById('file-name');
    
        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
    
            if (file) {
                fileNameDiv.textContent = `Fichier sélectionné : ${file.name}`;
            } else {
                fileNameDiv.textContent = 'Aucun fichier sélectionné';
            }
        });
        const videoInput = document.getElementById('file-upload-video');
        const videoNameDiv = document.getElementById('video-name');
        const documentInput = document.getElementById('file-upload-document');
        const documentNameDiv = document.getElementById('document-name');
    
        
        videoInput.addEventListener('change', (event) => {
            const file = event.target.files[0]; // Récupère le fichier sélectionné
            if (file) {
                videoNameDiv.textContent = `Fichier sélectionné : ${file.name}`;
            } else {
                videoNameDiv.textContent = 'Aucun fichier sélectionné';
            }
        });
    
        
        documentInput.addEventListener('change', (event) => {
            const file = event.target.files[0]; // Récupère le fichier sélectionné
            if (file) {
                documentNameDiv.textContent = `Fichier sélectionné : ${file.name}`;
            } else {
                documentNameDiv.textContent = 'Aucun fichier sélectionné';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tagsContainer = document.getElementById('tags-container');
            const selectedTagsHidden = document.getElementById('selected-tags-hidden');
            
            
            let selectedTags = [];
        
            
            function updateSelectedTagsInput() {
                selectedTagsHidden.value = selectedTags.join(','); 
            }
        
            
            tagsContainer.addEventListener('click', function(event) {
                const tagItem = event.target;
                
                if (tagItem.classList.contains('tag-item')) {
                    const tagId = tagItem.getAttribute('data-tag-id');
        
                    
                    if (selectedTags.includes(tagId)) {
                       
                        selectedTags = selectedTags.filter(tag => tag !== tagId);
                        tagItem.classList.remove('bg-orange-300'); 
                    } else {
                        
                        selectedTags.push(tagId);
                        tagItem.classList.add('bg-orange-300');
                    }
        
                    
                    updateSelectedTagsInput();
                }
            });
        });
        

        console.log('test');
        function openUpdateModal() {
            document.getElementById('update').classList.remove('hidden');
        }
        function closeModalupdate() {
            document.getElementById('update').classList.add('hidden');
        }