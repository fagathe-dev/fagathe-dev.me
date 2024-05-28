class ImagePreviewer {
  /**
   *
   * @param {Event} e
   * @param {object} options
   * @param {HTMLFormElement|null} form
   */
  constructor(e, options = {}) {
    this.options = options;
    this.form = e.target.closest("form") ?? null;
    this.imageContainer = document.querySelector("img.previewer-image");
    this.previewImage(e);
  }

  setOptions(options) {
    options = {
      maxFileSize: 2,
      preventSubmit: false,
      customImageClass: null,
      customImageID: null,
      customErrorClass: null,
      customErrorID: null,
      customErrorItemClass: null,
      ...options,
    };
    return options;
  }

  /**
   * Enable form submit button if uploaded file is valid
   * @returns {void|boolean}
   */
  enableSubmit() {
    if (
      this.options.preventSubmit === true &&
      this.form !== null &&
      this.hasErrors() === false
    ) {
      return this.form
        .querySelector('[type="submit"]')
        ?.removeAttribute("disabled");
    }
    return;
  }

  /**
   * Disable form submit button if uploaded file is valid
   * @returns {void|boolean}
   */
  disableSubmit() {
    if (
      this.options.preventSubmit === true &&
      this.form !== null &&
      this.hasErrors()
    ) {
      return this.form
        .querySelector('[type="submit"]')
        ?.setAttribute("disabled", true);
    }
    return;
  }

  /**
   * Return a list of trusted images extensions
   * @returns {string[]}
   */
  getTrustedImageTypes() {
    return ["jpg", "jpeg", "svg", "gif", "png", "avif", "webp", "apng"];
  }

  /**
   * Check if the uploaded image is valid
   * @param {File} image
   */
  checkImage(image) {
    const extension = image.name.split(".").pop();
    if (!this.getTrustedImageTypes().includes(extension)) {
      this.errors = {
        ...this.errors,
        type: "Cette image n'est pas valide !",
      };
    }

    this.checkImageSize(image);
  }

  /**
   * Preview the uploaded image
   * @param {Event} e
   * @returns {void}
   */
  previewImage(e) {
    const image = e.target.files[0];
    const node = e.target;
    this.errors = new Object();

    this.checkImage(image);

    this.displayErrorsMsg(node);

    return this.preview(image, node);
  }

  /**
   * Check image file size
   * @param {File} image
   */
  checkImageSize(image) {
    if (this.options.hasOwnProperty("maxFileSize")) {
      const allowedSize = this.convertToMb(this.options.maxFileSize);
      if (image.size > allowedSize) {
        this.errors = {
          ...this.errors,
          fileSize: "Ce fichier est trop volumineux !",
        };
      }
    }
  }

  /**
   *
   * @returns {boolean}
   */
  hasErrors() {
    return Object.keys(this.errors).length > 0;
  }

  /**
   *
   * @param {HTMLElement} node
   * @returns {HTMLDivElement}
   */
  getErrorContainer(node) {
    let target = document.querySelector("#errorsContainer"),
      container;
    const containerSelector = this.options.hasOwnProperty("errorsContainer");

    if (target !== null && target !== undefined) {
      container = target;
    } else if (container === null || container === undefined) {
      container = document.querySelector(containerSelector);
      if (container === null || container === undefined) {
        container = document.createElement("div");
        container.setAttribute(
          "id",
          this.options.customErrorID ?? "errorsContainer"
        );
        container.classList =
          this.options.customErrorClass ?? "previewer-error list";
        node.insertAdjacentElement("afterend", container);
      }
    } else if (target !== null && target !== undefined) {
      container = target;
    }

    return container;
  }

  /**
   *
   * @param {HTMLElement} node
   * @returns {void}
   */
  displayErrorsMsg(node) {
    let content = "",
      container = this.getErrorContainer(node);

    if (this.hasErrors() === false) {
      container.remove();
      this.enableSubmit();
    }

    if (this.imageContainer !== undefined && this.imageContainer !== null) {
      this.imageContainer.style.display = "none";
    }

    for (const key in this.errors) {
      if (this.errors.hasOwnProperty(key)) {
        content += `<p class="${
          this.options.customErrorItemClass ?? "previewer-error item"
        } text-danger">${key} : ${this.errors[key]}</p>`;
      }
    }

    this.disableSubmit();
    return (container.innerHTML = content);
  }

  niceSize(bytes) {
    const sizes = ["Bytes", "KB", "MB", "GB", "TB"];
    if (bytes === 0) {
      return "n/a";
    }
    const i = parseInt(
      Math.floor(Math.log(Math.abs(bytes)) / Math.log(1024)),
      10
    );
    if (i === 0) {
      return `${bytes} ${sizes[i]}`;
    }
    return `${(bytes / 1024 ** i).toFixed(1)} ${sizes[i]}`;
  }

  /**
   * Convert filesize to MB
   * @param {number} size
   * @param {string} type
   * @returns
   */
  convertToMb(size = 0, type = "MB") {
    const types = ["B", "KB", "MB", "GB", "TB"];

    const key = types.indexOf(type.toUpperCase());

    if (typeof key !== "boolean") {
      return parseInt(size) * 1024 ** key;
    }

    return "invalid type: type must be GB/KB/MB etc.";
  }

  /**
   *
   * @param {File} image
   * @param {HTMLElement} node
   * @returns
   */
  preview(image, node) {
    if (this.hasErrors()) {
      return;
    }

    let imageContainer = this.imageContainer;
    if (this.imageContainer === undefined || this.imageContainer === null) {
      imageContainer = document.createElement("img");
      imageContainer.classList =
        this.options.customImageClass ?? "previewer-image";
      if (
        this.options.hasOwnProperty("customImageID") &&
        this.options.customImageID !== null
      ) {
        imageContainer.id = this.options.customImageID;
      }

      node.insertAdjacentElement("beforebegin", imageContainer);
      if (this.options.hasOwnProperty("title")) {
        imageContainer.setAttribute("alt", this.options.title);
        imageContainer.setAttribute("title", this.options.title);
      }
      this.imageContainer = imageContainer;
    }
    this.imageContainer.style.display = "block";

    let urlImage = URL.createObjectURL(image);
    this.imageContainer.src = urlImage;
    this.imageContainer.srcset = urlImage;
    node.onload = () => URL.revokeObjectURL(this.imageContainer.src); // Free memory
    return;
  }
}
