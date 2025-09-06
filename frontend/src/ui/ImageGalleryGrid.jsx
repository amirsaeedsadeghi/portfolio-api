import PropTypes from "prop-types";
import ImageItem from "./ImageItem";
import { useState } from "react";

const sharedImgStyle =
  "object-cover rounded-lg cursor-pointer hover:opacity-90 transition";

ImageGalleryGrid.propTypes = {
  images: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
      image: PropTypes.string.isRequired,
    })
  ),
  primaryImage: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
};

function ImageGalleryGrid({ images, primaryImage, name }) {
  const [selectedImage, setSelectedImage] = useState(primaryImage);
  const [imagesArray, setImagesArray] = useState(images);

  function handleThumbnailClick(index) {
    const clickedImg = imagesArray[index];
    const newImagesArray = [...imagesArray];
    newImagesArray[index] = { id: clickedImg.id, image: selectedImage };
    setSelectedImage(clickedImg.image);
    setImagesArray([...newImagesArray]);
  }

  return (
    <div className="grid grid-cols-5 gap-4">
      <div className="col-span-4">
        <ImageItem
          src={selectedImage}
          alt={name ?? "main image"}
          className={sharedImgStyle}
        />
      </div>
      <div className="flex flex-col gap-4 col-span-1">
        {imagesArray?.map((image, index) => (
          <ImageItem
            src={image.image}
            alt={name ?? `image gallery ${index + 1}`}
            className={sharedImgStyle}
            key={`${image.id}-image-${index}`}
            onClick={() => handleThumbnailClick(index)}
          />
        ))}
      </div>
    </div>
  );
}

export default ImageGalleryGrid;
