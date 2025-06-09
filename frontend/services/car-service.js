const ShopService = {
    loadCars: function () {
        RestClient.get("cars", function (cars) {
            const searchInput = $("#searchInput").val().toLowerCase();
            const priceFilter = $("#priceFilter").val();
            const container = $("#carsContainer");
            container.empty();

            if (!cars || cars.length === 0) {
                container.append("<p>No cars available.</p>");
                return;
            }

            let filteredCars = cars.filter(car => {
                const matchesSearch =
                    car.model.model_name.toLowerCase().includes(searchInput) ||
                    car.model.brand.brand_name.toLowerCase().includes(searchInput);

                let matchesPrice = true;
                if (priceFilter) {
                    const [min, max] = priceFilter.split('-').map(Number);
                    matchesPrice = car.price >= min && car.price <= max;
                }

                return matchesSearch && matchesPrice;
            });

            if (filteredCars.length === 0) {
                container.append("<p>No cars match your criteria.</p>");
                return;
            }

            filteredCars.forEach(car => {
                const card = `
                    <div class="car-card">
                        <img src="assets/img/toyota.jpg" alt="Car Image" class="car-img">
                        <h4>${car.model.model_name} (${car.model.brand.brand_name})</h4>
                        <p><strong>Price:</strong> $${car.price}</p>
                        <p><strong>Mileage:</strong> ${car.mileage} km</p>
                        <p><strong>Color:</strong> ${car.color}</p>
                        <p><strong>State:</strong> ${car.state}</p>
                        <p><strong>Description:</strong> ${car.description || 'No description'}</p>
                    </div>
                `;
                container.append(card);
            });
        });
    },

    setupFilters: function () {
        $("#searchInput, #priceFilter").on("input change", function () {
            ShopService.loadCars();
        });
    }
};



let CarService = {
    init: function () {
        $("#addCarForm").validate({
            rules: {
                model: 'required',
                price: { required: true, number: true },
                mileage: { required: true, number: true }
            },
            submitHandler: function (form) {
                let car = Object.fromEntries(new FormData(form).entries());
                const userId = getCurrentUserId();
                if (!userId) {
                    toastr.error("You're not logged in.");
                    return;
                }
                car.seller = userId;
                car.image = "assets/img/toyota.jpg"; // Static image
                delete car.brand;
                CarService.addCar(car);
                form.reset();
            }
        });

        $("#editCarForm").validate({
            submitHandler: function (form) {
                let car = Object.fromEntries(new FormData(form).entries());
                CarService.editCar(car);
            }
        });

        CarService.getAllCars();
    },

    openAddModal: function () {
        RestClient.get('brands', function (brands) {
            $('#brand').empty().append('<option value="">Select Brand</option>');
            brands.forEach(brand => {
                $('#brand').append(`<option value="${brand.id}">${brand.brand_name}</option>`);
            });
        });
        $('#addCarModal').show();
    },

    loadModels: function (brandId) {
        if (brandId) {
            RestClient.get(`models?brand_id=${brandId}`, function (models) {
                $('#model').empty().append('<option value="">Select Model</option>');
                models.forEach(model => {
                    $('#model').append(`<option value="${model.id}">${model.model_name}</option>`);
                });
            });
        }
    },

    loadModelsForEdit: function (brandId) {
        if (brandId) {
            RestClient.get(`models?brand_id=${brandId}`, function (models) {
                $('#edit_model').empty().append('<option value="">Select Model</option>');
                models.forEach(model => {
                    $('#edit_model').append(`<option value="${model.id}">${model.model_name}</option>`);
                });
            });
        }
    },

    addCar: function (car) {
        $.blockUI({ message: '<h3>Processing...</h3>' });
        RestClient.post('cars', car, function () {
            toastr.success("Car added successfully");
            $.unblockUI();
            CarService.getAllCars();
            CarService.closeModal();
        }, function (response) {
            toastr.error(response.message);
            $.unblockUI();
            CarService.closeModal();
        });
    },

    getAllCars: function () {
        RestClient.get("cars", function (data) {
            Utils.datatable('cars-table', [
                { data: 'id', title: 'ID' },
                {
                    data: 'model',
                    title: 'Model',
                    render: function (data, type, row) {
                        const modelName = row.model?.model_name || 'N/A';
                        const brandName = row.model?.brand?.brand_name || 'N/A';
                        return `${modelName} (${brandName})`;
                    }
                },
                { data: 'price', title: 'Price' },
                { data: 'mileage', title: 'Mileage' },
                { data: 'color', title: 'Color' },
                { data: 'state', title: 'Condition' },
                {
                    title: 'Actions',
                    render: function (data, type, row) {
                        const rowStr = encodeURIComponent(JSON.stringify(row));
                        return `
                            <div class="d-flex justify-content-center gap-2 mt-3">
                                <button class="btn btn-primary" onclick="CarService.openEditModal('${row.id}')">Edit</button>
                                <button class="btn btn-danger" onclick="CarService.openConfirmationDialog(decodeURIComponent('${rowStr}'))">Delete</button>
                                <button class="btn btn-secondary" onclick="CarService.openViewMore('${row.id}')">Details</button>
                            </div>`;
                    }
                }
            ], data, 10);
        });
    },

    getCarById: function (id) {
        RestClient.get('cars/' + id, function (data) {
            localStorage.setItem('selected_car', JSON.stringify(data));
            $('#edit_car_id').val(data.id);
            $('#edit_price').val(data.price);
            $('#edit_mileage').val(data.mileage);
            $('#edit_color').val(data.color);
            $('#edit_state').val(data.state);
            $('#edit_description').val(data.description);

            RestClient.get('brands', function (brands) {
                $('#edit_brand').empty().append('<option value="">Select Brand</option>');
                brands.forEach(brand => {
                    $('#edit_brand').append(`<option value="${brand.id}">${brand.brand_name}</option>`);
                });

                RestClient.get('models/' + data.model.id, function (modelData) {
                    const brandId = modelData.brand.id;
                    $('#edit_brand').val(brandId);
                    CarService.loadModelsForEdit(brandId);
                    setTimeout(() => {
                        $('#edit_model').val(modelData.id);
                    }, 200);
                });
            });

            $.unblockUI();
        });
    },

    editCar: function (car) {
        $.blockUI({ message: '<h3>Processing...</h3>' });
        RestClient.put('cars/' + car.id, car, function () {
            toastr.success("Car updated successfully");
            CarService.closeModal();
            CarService.getAllCars();
            $.unblockUI();
        }, function () {
            toastr.error("Update failed");
            $.unblockUI();
        });
    },

    openEditModal: function (id) {
        $.blockUI({ message: '<h3>Loading...</h3>' });
        $('#editCarModal').show();
        CarService.getCarById(id);
    },

    openConfirmationDialog: function (car) {
        car = JSON.parse(car);
        $("#deleteCarModal").modal("show");
        $("#delete-car-body").html(`Are you sure you want to delete this car: ${car.model.model_name} (${car.model.brand.brand_name})?`);
        $("#delete_car_id").val(car.id);
    },

    deleteCar: function () {
        RestClient.delete('cars/' + $("#delete_car_id").val(), null, function (response) {
            CarService.closeModal();
            toastr.success(response.message);
            CarService.getAllCars();
        }, function (response) {
            CarService.closeModal();
            toastr.error(response.message);
        });
    },

    openViewMore: function (id) {
        window.location.replace("#shop");
        CarService.getCarById(id);
    },

    populateViewMore: function () {
        let car = JSON.parse(localStorage.getItem('selected_car'));
        $("#car-model").text(`${car.model.model_name} (${car.model.brand.brand_name})`);
        $("#car-price").text(car.price);
        $("#car-mileage").text(car.mileage);
        $("#car-color").text(car.color);
        $("#car-state").text(car.state);
        $("#car-description").text(car.description || "No description available.");
    },

    closeModal: function () {
        $('#editCarModal').hide();
        $("#deleteCarModal").modal("hide");
        $('#addCarModal').hide();
    }
};
