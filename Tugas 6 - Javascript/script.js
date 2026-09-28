const products = [
  {
    id: 1,
    name: "MacBook Air M3",
    price: 18999000,
    category: "Elektronik",
    description: "Laptop ringan dengan performa tinggi untuk produktivitas dan kebutuhan profesional.",
    image: "https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 2,
    name: "Smartphone Nova Pro",
    price: 5499000,
    category: "Elektronik",
    description: "Smartphone modern dengan layar jernih dan performa cepat untuk penggunaan harian.",
    image: "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 3,
    name: "Wireless Headphone",
    price: 899000,
    category: "Elektronik",
    description: "Headphone wireless dengan kualitas audio jernih dan desain nyaman.",
    image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 4,
    name: "Smartwatch Active",
    price: 1399000,
    category: "Elektronik",
    description: "Smartwatch untuk memantau aktivitas, olahraga dan notifikasi harian.",
    image: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 5,
    name: "Mechanical Keyboard",
    price: 749000,
    category: "Elektronik",
    description: "Keyboard mechanical responsif dengan desain modern untuk bekerja dan gaming.",
    image: "https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 6,
    name: "Wireless Mouse",
    price: 329000,
    category: "Elektronik",
    description: "Mouse wireless ergonomis dengan sensor presisi untuk produktivitas.",
    image: "https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 7,
    name: "Bluetooth Speaker",
    price: 499000,
    category: "Elektronik",
    description: "Speaker portabel dengan suara powerful dan konektivitas Bluetooth.",
    image: "https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 8,
    name: "Gaming Monitor 24 Inch",
    price: 2499000,
    category: "Elektronik",
    description: "Monitor dengan refresh rate tinggi untuk gaming dan pekerjaan multimedia.",
    image: "https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 9,
    name: "Portable SSD 1TB",
    price: 1599000,
    category: "Elektronik",
    description: "Penyimpanan portable berkecepatan tinggi dengan kapasitas besar.",
    image: "https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 10,
    name: "Power Bank Fast Charging",
    price: 399000,
    category: "Elektronik",
    description: "Power bank kapasitas besar dengan teknologi fast charging.",
    image: "https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?auto=format&fit=crop&w=600&q=80",
  },

  {
    id: 11,
    name: "Oversized T-Shirt",
    price: 149000,
    category: "Fashion",
    description: "Kaos oversized dengan bahan cotton premium yang nyaman digunakan.",
    image: "https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 12,
    name: "Classic Hoodie",
    price: 299000,
    category: "Fashion",
    description: "Hoodie casual dengan bahan lembut dan nyaman untuk aktivitas sehari-hari.",
    image: "https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 13,
    name: "Denim Jacket",
    price: 429000,
    category: "Fashion",
    description: "Jaket denim klasik yang cocok digunakan untuk berbagai gaya.",
    image: "https://images.unsplash.com/photo-1523205771623-e0faa4d2813d?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 14,
    name: "Casual Sneakers",
    price: 599000,
    category: "Fashion",
    description: "Sneakers modern dengan desain minimalis untuk aktivitas sehari-hari.",
    image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 15,
    name: "Slim Fit Chino",
    price: 279000,
    category: "Fashion",
    description: "Celana chino dengan potongan slim fit untuk tampilan casual modern.",
    image: "https://images.unsplash.com/photo-1473966968600-fa801b869a1a?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 16,
    name: "Casual Shirt",
    price: 239000,
    category: "Fashion",
    description: "Kemeja casual dengan bahan nyaman untuk aktivitas sehari-hari.",
    image: "https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 17,
    name: "Canvas Backpack",
    price: 349000,
    category: "Fashion",
    description: "Tas backpack modern dengan ruang penyimpanan luas.",
    image: "https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 18,
    name: "Baseball Cap",
    price: 129000,
    category: "Fashion",
    description: "Topi baseball bergaya sederhana untuk melengkapi penampilan casual.",
    image: "https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 19,
    name: "Running Shoes",
    price: 749000,
    category: "Fashion",
    description: "Sepatu ringan dengan bantalan nyaman untuk berbagai aktivitas.",
    image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 20,
    name: "Leather Wallet",
    price: 199000,
    category: "Fashion",
    description: "Dompet kulit minimalis dengan desain elegan dan penyimpanan praktis.",
    image: "https://images.unsplash.com/photo-1627123424574-724758594e93?auto=format&fit=crop&w=600&q=80",
  },

  {
    id: 21,
    name: "Minimalist Table Lamp",
    price: 249000,
    category: "Rumah",
    description: "Lampu meja minimalis untuk menciptakan suasana ruangan lebih nyaman.",
    image: "https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 22,
    name: "Modern Sofa Cushion",
    price: 129000,
    category: "Rumah",
    description: "Bantal sofa lembut yang cocok untuk dekorasi interior modern.",
    image: "https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 23,
    name: "Wooden Wall Clock",
    price: 289000,
    category: "Rumah",
    description: "Jam dinding dengan desain kayu minimalis untuk dekorasi rumah.",
    image: "https://images.unsplash.com/photo-1563861826100-9cb868fdbe1c?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 24,
    name: "Ceramic Mug",
    price: 89000,
    category: "Rumah",
    description: "Mug keramik dengan desain simpel untuk menikmati minuman favorit.",
    image: "https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 25,
    name: "Coffee Maker",
    price: 799000,
    category: "Rumah",
    description: "Mesin pembuat kopi praktis untuk menikmati kopi setiap hari.",
    image: "https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 26,
    name: "Electric Kettle",
    price: 299000,
    category: "Rumah",
    description: "Teko listrik dengan pemanasan cepat untuk kebutuhan dapur.",
    image: "https://images.unsplash.com/photo-1594213114663-d94db9b17125?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 27,
    name: "Minimalist Chair",
    price: 549000,
    category: "Rumah",
    description: "Kursi minimalis dengan desain modern untuk ruang kerja atau ruang tamu.",
    image: "https://images.unsplash.com/photo-1592078615290-033ee584e267?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 28,
    name: "Wooden Bookshelf",
    price: 899000,
    category: "Rumah",
    description: "Rak buku berbahan kayu dengan desain sederhana dan elegan.",
    image: "https://images.unsplash.com/photo-1594620302200-9a762244a156?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 29,
    name: "Decorative Plant",
    price: 159000,
    category: "Rumah",
    description: "Tanaman dekoratif untuk mempercantik meja atau ruangan.",
    image: "https://images.unsplash.com/photo-1485955900006-10f4d324d411?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 30,
    name: "Bedside Lamp",
    price: 229000,
    category: "Rumah",
    description: "Lampu tidur dengan cahaya hangat untuk menciptakan suasana nyaman.",
    image: "https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?auto=format&fit=crop&w=600&q=80",
  },

  {
    id: 31,
    name: "Yoga Mat",
    price: 229000,
    category: "Olahraga",
    description: "Matras yoga anti slip yang nyaman untuk latihan di rumah.",
    image: "https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 32,
    name: "Dumbbell Set",
    price: 499000,
    category: "Olahraga",
    description: "Set dumbbell untuk latihan kekuatan dan kebugaran di rumah.",
    image: "https://images.unsplash.com/photo-1586401100295-7a8096fd231a?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 33,
    name: "Running Bottle",
    price: 119000,
    category: "Olahraga",
    description: "Botol minum ringan yang praktis untuk olahraga dan aktivitas outdoor.",
    image: "https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 34,
    name: "Basketball",
    price: 329000,
    category: "Olahraga",
    description: "Bola basket dengan grip nyaman untuk penggunaan indoor dan outdoor.",
    image: "https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 35,
    name: "Training Gloves",
    price: 159000,
    category: "Olahraga",
    description: "Sarung tangan gym dengan grip kuat untuk latihan angkat beban.",
    image: "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 36,
    name: "Resistance Band",
    price: 139000,
    category: "Olahraga",
    description: "Resistance band untuk latihan kekuatan dan fleksibilitas tubuh.",
    image: "https://images.unsplash.com/photo-1598289431512-b97b0917affc?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 37,
    name: "Skipping Rope",
    price: 99000,
    category: "Olahraga",
    description: "Skipping rope ringan untuk latihan cardio yang efektif.",
    image: "https://images.unsplash.com/photo-1599058917212-d750089bc07e?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 38,
    name: "Gym Bag",
    price: 299000,
    category: "Olahraga",
    description: "Tas olahraga dengan kompartemen luas untuk membawa perlengkapan gym.",
    image: "https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 39,
    name: "Training Shirt",
    price: 179000,
    category: "Olahraga",
    description: "Kaos olahraga berbahan breathable yang nyaman saat beraktivitas.",
    image: "https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 40,
    name: "Fitness Tracker",
    price: 599000,
    category: "Olahraga",
    description: "Fitness tracker untuk memantau aktivitas dan perkembangan kebugaran.",
    image: "https://images.unsplash.com/photo-1575311373937-040b8e1fd5b6?auto=format&fit=crop&w=600&q=80",
  },

  {
    id: 41,
    name: "Classic Sunglasses",
    price: 269000,
    category: "Aksesoris",
    description: "Kacamata hitam dengan desain klasik untuk menunjang penampilan.",
    image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 42,
    name: "Analog Watch",
    price: 599000,
    category: "Aksesoris",
    description: "Jam tangan analog dengan desain klasik dan elegan.",
    image: "https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 43,
    name: "Leather Belt",
    price: 189000,
    category: "Aksesoris",
    description: "Ikat pinggang dengan desain minimalis untuk tampilan formal maupun casual.",
    image: "https://images.unsplash.com/photo-1624222247344-550fb60583dc?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 44,
    name: "Minimalist Bracelet",
    price: 149000,
    category: "Aksesoris",
    description: "Gelang dengan desain minimalis untuk melengkapi gaya sehari-hari.",
    image: "https://images.unsplash.com/photo-1611652022419-a9419f74343d?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 45,
    name: "Canvas Tote Bag",
    price: 169000,
    category: "Aksesoris",
    description: "Tas tote sederhana dengan kapasitas besar untuk kebutuhan harian.",
    image: "https://images.unsplash.com/photo-1594223274512-ad4803739b7c?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 46,
    name: "Laptop Sleeve",
    price: 199000,
    category: "Aksesoris",
    description: "Sleeve laptop dengan lapisan lembut untuk perlindungan tambahan.",
    image: "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 47,
    name: "Phone Case",
    price: 99000,
    category: "Aksesoris",
    description: "Case smartphone dengan desain sederhana dan perlindungan optimal.",
    image: "https://images.unsplash.com/photo-1601593346740-925612772716?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 48,
    name: "Travel Organizer",
    price: 219000,
    category: "Aksesoris",
    description: "Organizer praktis untuk menyimpan berbagai perlengkapan perjalanan.",
    image: "https://images.unsplash.com/photo-1553531384-cc64ac80f931?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 49,
    name: "Laptop Stand",
    price: 349000,
    category: "Aksesoris",
    description: "Stand laptop ergonomis untuk meningkatkan kenyamanan saat bekerja.",
    image: "https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=600&q=80",
  },
  {
    id: 50,
    name: "Cable Organizer",
    price: 79000,
    category: "Aksesoris",
    description: "Organizer kabel sederhana agar meja kerja terlihat lebih rapi.",
    image: "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=600&q=80",
  },
];

/* =========================
   DOM
========================= */

const productList = document.getElementById("productList");
const searchInput = document.getElementById("searchInput");
const categoryFilter = document.getElementById("categoryFilter");
const sortFilter = document.getElementById("sortFilter");
const resetFilter = document.getElementById("resetFilter");
const productCount = document.getElementById("productCount");
const emptyState = document.getElementById("emptyState");
const cartCountElement = document.getElementById("cartCount");

const modalImage = document.getElementById("modalImage");
const modalName = document.getElementById("modalName");
const modalCategory = document.getElementById("modalCategory");
const modalPrice = document.getElementById("modalPrice");
const modalDescription = document.getElementById("modalDescription");
const modalCartButton = document.getElementById("modalCartButton");

let cartCount = 0;
let selectedProductId = null;

/* =========================
   FORMAT RUPIAH
========================= */

function formatRupiah(value) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
  }).format(value);
}

/* =========================
   CATEGORY
========================= */

function loadCategories() {
  const categories = [...new Set(products.map((product) => product.category))];

  categories.sort();

  categories.forEach((category) => {
    const option = document.createElement("option");

    option.value = category;
    option.textContent = category;

    categoryFilter.appendChild(option);
  });
}

/* =========================
   PRODUCT CARD
========================= */

function createProductCard(product) {
  return `
    <div class="col-sm-6 col-lg-4 col-xl-3">

      <div class="product-card">

        <div class="product-image-wrapper">

          <img
            src="${product.image}"
            class="product-image"
            alt="${product.name}"
            loading="lazy"
          >

          <button
            class="quick-view"
            onclick="showProductDetail(${product.id})"
            title="Lihat Detail"
          >
            <i class="bi bi-eye"></i>
          </button>

        </div>

        <div class="product-content">

          <span class="product-category">
            ${product.category}
          </span>

          <h5 class="mt-3">
            ${product.name}
          </h5>

          <p class="product-description">
            ${product.description}
          </p>

          <div
            class="d-flex justify-content-between align-items-center mt-3"
          >

            <div class="product-price">
              ${formatRupiah(product.price)}
            </div>

            <button
              class="add-cart-button"
              onclick="addToCart(${product.id})"
              title="Tambah ke Keranjang"
            >
              <i class="bi bi-cart-plus"></i>
            </button>

          </div>

        </div>

      </div>

    </div>
  `;
}

/* =========================
   FILTER & SORT
========================= */

function renderProducts() {
  const keyword = searchInput.value.toLowerCase().trim();
  const category = categoryFilter.value;
  const sort = sortFilter.value;

  let filteredProducts = products.filter((product) => {
    const matchSearch = product.name.toLowerCase().includes(keyword) || product.description.toLowerCase().includes(keyword);

    const matchCategory = category === "all" || product.category === category;

    return matchSearch && matchCategory;
  });

  if (sort === "name-asc") {
    filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
  }

  if (sort === "name-desc") {
    filteredProducts.sort((a, b) => b.name.localeCompare(a.name));
  }

  if (sort === "price-asc") {
    filteredProducts.sort((a, b) => a.price - b.price);
  }

  if (sort === "price-desc") {
    filteredProducts.sort((a, b) => b.price - a.price);
  }

  productCount.textContent = filteredProducts.length;

  if (filteredProducts.length === 0) {
    productList.innerHTML = "";

    emptyState.classList.remove("d-none");

    return;
  }

  emptyState.classList.add("d-none");

  productList.innerHTML = filteredProducts.map((product) => createProductCard(product)).join("");
}

/* =========================
   PRODUCT DETAIL
========================= */

function showProductDetail(id) {
  const product = products.find((product) => product.id === id);

  if (!product) {
    return;
  }

  selectedProductId = id;

  modalImage.src = product.image;
  modalImage.alt = product.name;

  modalName.textContent = product.name;
  modalCategory.textContent = product.category;
  modalPrice.textContent = formatRupiah(product.price);
  modalDescription.textContent = product.description;

  const productModal = new bootstrap.Modal(document.getElementById("productModal"));

  productModal.show();
}

/* =========================
   CART
========================= */

function addToCart(id) {
  const product = products.find((product) => product.id === id);

  if (!product) {
    return;
  }

  cartCount++;

  cartCountElement.textContent = cartCount;

  const toastElement = document.getElementById("cartToast");

  const toast = bootstrap.Toast.getOrCreateInstance(toastElement);

  toastElement.querySelector(".toast-body").textContent = `${product.name} berhasil ditambahkan ke keranjang.`;

  toast.show();
}

modalCartButton.addEventListener("click", () => {
  if (selectedProductId !== null) {
    addToCart(selectedProductId);
  }
});

/* =========================
   RESET FILTER
========================= */

function resetFilters() {
  searchInput.value = "";
  categoryFilter.value = "all";
  sortFilter.value = "default";

  renderProducts();
}

/* =========================
   EVENT LISTENER
========================= */

searchInput.addEventListener("input", renderProducts);

categoryFilter.addEventListener("change", renderProducts);

sortFilter.addEventListener("change", renderProducts);

resetFilter.addEventListener("click", resetFilters);

/* =========================
   INITIAL
========================= */

loadCategories();

renderProducts();
