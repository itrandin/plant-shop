document.addEventListener("DOMContentLoaded", function () {

    function goToLogin() {
        window.location.href = "login.php";
    }

    function showToast(message, type = "success") {
        let toast = document.querySelector(".site-toast");

        if (!toast) {
            toast = document.createElement("div");
            toast.className = "site-toast";
            document.body.appendChild(toast);
        }

        toast.textContent = message;
        toast.className = "site-toast is-visible " + (type === "error" ? "site-toast--error" : "site-toast--success");

        clearTimeout(window.__siteToastTimer);
        window.__siteToastTimer = setTimeout(() => {
            toast.classList.remove("is-visible");
        }, 2200);
    }

    function updateHeaderCartCounter(count) {
        const cartCounter = document.getElementById("cart-counter");

        if (!cartCounter) {
            return;
        }

        const numericCount = Number(count) || 0;
        cartCounter.textContent = numericCount;
        cartCounter.style.display = numericCount > 0 ? "flex" : "none";
    }

    function updateCartTotals(data) {
        const cartCountText = document.querySelector("[data-cart-count-text]");
        const cartTotalText = document.querySelector("[data-cart-total]");
        const deliveryText = document.querySelector("[data-delivery-price]");
        const finalTotalText = document.querySelector("[data-final-total]");

        if (cartCountText) {
            cartCountText.textContent = data.cart_count + " " + getProductWord(Number(data.cart_count));
        }

        if (cartTotalText) {
            cartTotalText.textContent = data.cart_total;
        }

        if (deliveryText) {
            deliveryText.textContent = data.delivery_price;
        }

        if (finalTotalText) {
            finalTotalText.textContent = data.final_total;
        }

        updateHeaderCartCounter(data.cart_count);

        if (Number(data.cart_count) <= 0) {
            showEmptyCart();
        }
    }

    function getProductWord(count) {
        const lastDigit = count % 10;
        const lastTwoDigits = count % 100;

        if (lastDigit === 1 && lastTwoDigits !== 11) {
            return "товар";
        }

        if ([2, 3, 4].includes(lastDigit) && ![12, 13, 14].includes(lastTwoDigits)) {
            return "товара";
        }

        return "товаров";
    }

    function showEmptyCart() {
        const cartList = document.querySelector("[data-cart-list]");
        const checkoutButton = document.querySelector(".cart-submit");

        if (cartList) {
            cartList.innerHTML = '<div class="empty-account-message">Ваша корзина пока пуста.</div>';
        }

        if (checkoutButton) {
            checkoutButton.disabled = true;
        }
    }


    // Меню профиля по клику
    const profileMenu = document.querySelector(".profile-menu");
    const profileMenuButton = document.querySelector(".profile-menu__btn");

    if (profileMenu && profileMenuButton) {
        profileMenuButton.addEventListener("click", function (event) {
            event.stopPropagation();
            profileMenu.classList.toggle("is-open");
        });

        document.addEventListener("click", function (event) {
            if (!profileMenu.contains(event.target)) {
                profileMenu.classList.remove("is-open");
            }
        });
    }

    function updateAccountFavoritesCount(delta) {
        const countElement = document.querySelector("[data-account-favorites-count]");

        if (!countElement) {
            return;
        }

        const currentValue = Number(countElement.textContent) || 0;
        const newValue = Math.max(0, currentValue + delta);
        countElement.textContent = newValue;
    }

    function showEmptyFavoritesIfNeeded() {
        const favoritesGrid = document.querySelector(".favorite-plants-grid");
        const favoritesCard = document.querySelector(".account-favorites-card");

        if (!favoritesGrid || !favoritesCard) {
            return;
        }

        if (favoritesGrid.querySelectorAll("[data-favorite-item]").length === 0) {
            favoritesGrid.remove();

            const emptyMessage = document.createElement("div");
            emptyMessage.className = "empty-account-message";
            emptyMessage.textContent = "Вы пока не добавили растения в избранное.";
            favoritesCard.appendChild(emptyMessage);
        }
    }


    // Поиск по странице ухода
    const careSearchInput = document.getElementById("care-search-input");
    const careCards = document.querySelectorAll(".care-plant-card");

    if (careSearchInput && careCards.length) {
        const careList = document.querySelector(".care-list");
        const emptyMessage = document.createElement("div");
        emptyMessage.className = "empty-account-message care-empty-message";
        emptyMessage.textContent = "По вашему запросу рекомендации не найдены.";
        emptyMessage.style.display = "none";

        if (careList) {
            careList.appendChild(emptyMessage);
        }

        careSearchInput.addEventListener("input", function () {
            const query = this.value.trim().toLowerCase();
            let visibleCount = 0;

            careCards.forEach(card => {
                const cardText = card.textContent.toLowerCase();
                const isVisible = cardText.includes(query);
                card.style.display = isVisible ? "grid" : "none";

                if (isVisible) {
                    visibleCount++;
                }
            });

            emptyMessage.style.display = visibleCount === 0 ? "block" : "none";
        });
    }



    // Живой поиск по каталогу без нажатия Enter
    const catalogSearchInput = document.querySelector('.catalog-tools input[name="q"]');
    const catalogCards = document.querySelectorAll('.catalog-card');

    if (catalogSearchInput && catalogCards.length) {
        const catalogProducts = document.querySelector('.catalog-products');
        let emptyMessage = document.querySelector('.catalog-live-empty-message');

        if (!emptyMessage && catalogProducts) {
            emptyMessage = document.createElement('div');
            emptyMessage.className = 'empty-account-message catalog-live-empty-message';
            emptyMessage.textContent = 'По вашему запросу растения не найдены.';
            emptyMessage.style.display = 'none';
            catalogProducts.appendChild(emptyMessage);
        }

        catalogSearchInput.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            let visibleCount = 0;

            catalogCards.forEach(card => {
                const cardText = card.textContent.toLowerCase();
                const isVisible = cardText.includes(query);
                card.style.display = isVisible ? '' : 'none';

                if (isVisible) {
                    visibleCount++;
                }
            });

            if (emptyMessage) {
                emptyMessage.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });
    }

    // Избранное
    const favoriteButtons = document.querySelectorAll(".favorite-btn");

    favoriteButtons.forEach(button => {
        button.addEventListener("click", function () {
            const plantId = this.dataset.plantId;

            fetch("actions/favorite_action.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id_plant=" + encodeURIComponent(plantId)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    goToLogin();
                    return;
                }

                const heart = this.querySelector("img");

                if (data.status === "added") {
                    heart.src = "images/heartV2.svg";
                }

                if (data.status === "removed") {
                    heart.src = "images/heart.svg";
                }
            })
            .catch(() => {
                showToast("Ошибка при добавлении в избранное.", "error");
            });
        });
    });

    // Удаление растения из избранного в личном кабинете
    const accountFavoriteRemoveButtons = document.querySelectorAll(".favorite-remove-btn");

    accountFavoriteRemoveButtons.forEach(button => {
        button.addEventListener("click", function () {
            const plantId = this.dataset.plantId;
            const item = this.closest("[data-favorite-item]");

            fetch("actions/favorite_action.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id_plant=" + encodeURIComponent(plantId)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    goToLogin();
                    return;
                }

                if (data.status === "removed" && item) {
                    item.remove();
                    updateAccountFavoritesCount(-1);
                    showEmptyFavoritesIfNeeded();
                }
            })
            .catch(() => {
                showToast("Ошибка при удалении из избранного.", "error");
            });
        });
    });

    // Добавление товара в корзину из каталога и главной
    const catalogCartButtons = document.querySelectorAll(".catalog-cart-btn");

    catalogCartButtons.forEach(button => {
        button.addEventListener("click", function () {
            const plantId = this.dataset.plantId;
            const isHomeButton = this.classList.contains("product-cart");
            const originalHTML = this.innerHTML;
            const originalText = this.textContent;

            fetch("actions/cart_add.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id_plant=" + encodeURIComponent(plantId)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    goToLogin();
                    return;
                }

                updateHeaderCartCounter(data.cart_count);
                showToast("Товар добавлен в корзину.");

                this.disabled = true;

                if (!isHomeButton) {
                    this.textContent = "Добавлено";
                } else {
                    this.innerHTML = originalHTML;
                }

                setTimeout(() => {
                    if (!isHomeButton) {
                        this.textContent = originalText;
                    } else {
                        this.innerHTML = originalHTML;
                    }

                    this.disabled = false;
                }, 1200);
            })
            .catch(() => {
                showToast("Ошибка при добавлении в корзину.", "error");
            });
        });
    });

    // Изменение количества товара в корзине
    const quantityButtons = document.querySelectorAll(".cart-qty-btn");

    quantityButtons.forEach(button => {
        button.addEventListener("click", function () {
            const cartId = this.dataset.cartId;
            const action = this.dataset.action;
            const item = this.closest(".cart-product-item");

            fetch("actions/cart_update.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id_cart=" + encodeURIComponent(cartId) + "&action=" + encodeURIComponent(action)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    goToLogin();
                    return;
                }

                if (data.removed) {
                    item.remove();
                } else {
                    const quantityText = item.querySelector("[data-cart-quantity]");
                    const itemTotalText = item.querySelector("[data-item-total]");

                    if (quantityText) {
                        quantityText.textContent = data.quantity;
                    }

                    if (itemTotalText) {
                        itemTotalText.textContent = data.item_total;
                    }
                }

                updateCartTotals(data);
            })
            .catch(() => {
                showToast("Ошибка при изменении количества товара.", "error");
            });
        });
    });

    // Удаление товара из корзины
    const removeButtons = document.querySelectorAll(".cart-remove");

    removeButtons.forEach(button => {
        button.addEventListener("click", function () {
            const cartId = this.dataset.cartId;
            const item = this.closest(".cart-product-item");

            fetch("actions/cart_remove.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id_cart=" + encodeURIComponent(cartId)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    goToLogin();
                    return;
                }

                item.remove();
                updateCartTotals(data);
            })
            .catch(() => {
                showToast("Ошибка при удалении товара из корзины.", "error");
            });
        });
    });

    // Живой поиск и сортировка отзывов
    const reviewsSearchInput = document.getElementById("reviews-search-input");
    const reviewsSortSelect = document.getElementById("reviews-sort-select");
    const reviewsGrid = document.getElementById("reviews-grid");
    const reviewsEmptyMessage = document.getElementById("reviews-empty-message");

    function updateReviewsList() {
        if (!reviewsGrid) {
            return;
        }

        const query = reviewsSearchInput ? reviewsSearchInput.value.trim().toLowerCase() : "";
        const sortValue = reviewsSortSelect ? reviewsSortSelect.value : "new";
        const cards = Array.from(reviewsGrid.querySelectorAll("[data-review-card]"));

        cards.sort((a, b) => {
            if (sortValue === "old") {
                return Number(a.dataset.date) - Number(b.dataset.date);
            }

            if (sortValue === "rating_desc") {
                return Number(b.dataset.rating) - Number(a.dataset.rating);
            }

            if (sortValue === "rating_asc") {
                return Number(a.dataset.rating) - Number(b.dataset.rating);
            }

            if (sortValue === "plant") {
                return (a.dataset.plant || "").localeCompare(b.dataset.plant || "", "ru");
            }

            return Number(b.dataset.date) - Number(a.dataset.date);
        });

        cards.forEach(card => reviewsGrid.appendChild(card));

        let visibleCount = 0;

        cards.forEach(card => {
            const text = (card.dataset.search || "").toLowerCase();
            const isVisible = text.includes(query);
            card.style.display = isVisible ? "" : "none";

            if (isVisible) {
                visibleCount++;
            }
        });

        if (reviewsEmptyMessage) {
            reviewsEmptyMessage.style.display = visibleCount === 0 ? "block" : "none";
        }
    }

    if (reviewsSearchInput) {
        reviewsSearchInput.addEventListener("input", updateReviewsList);
    }

    if (reviewsSortSelect) {
        reviewsSortSelect.addEventListener("change", updateReviewsList);
    }

    const reviewDeleteForms = document.querySelectorAll(".review-delete-form");

    reviewDeleteForms.forEach(form => {
        form.addEventListener("submit", function (event) {
            if (!confirm("Удалить этот отзыв?")) {
                event.preventDefault();
            }
        });
    });

    const apiCareButtons = document.querySelectorAll(".api-care-btn");

    apiCareButtons.forEach(button => {
        button.addEventListener("click", function () {
            const plantName = this.dataset.plant;
            const resultBox = this.nextElementSibling;

            resultBox.textContent = "Загрузка...";

            fetch("actions/plant_api.php?q=" + encodeURIComponent(plantName))
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        resultBox.textContent = data.message;
                        return;
                    }

                    resultBox.innerHTML = `
                        <b>${data.name}</b><br>
                        Научное название: ${data.scientific_name}<br>
                        Семейство: ${data.family}<br>
                        Род: ${data.genus}<br>
                        Год описания: ${data.year}
                    `;
                })
                .catch(() => {
                    resultBox.textContent = "Ошибка загрузки данных.";
                });
        });
    });

});
