import { FaArrowLeft } from "react-icons/fa6";
import { Link } from "react-router-dom";

function PageNotFound() {
  return (
    <section className="min-h-screen flex flex-col justify-center items-center text-light text-center bg-cover bg-center relative px-6 page-not-found">
      <div className="absolute inset-0 bg-black bg-opacity-70"></div>
      <div className="relative z-10 flex flex-col items-center gap-8">
        <div className="perspective-container">
          <h1 className="text-9xl font-extrabold tracking-widest text-primary fall-back depth-text">
            404
          </h1>
        </div>
        <div className="bg-bg-primary px-6 py-4 rounded-lg animate-pulse">
          <h2 className="text-3xl font-bold mb-2">Page Not Found</h2>
          <p className="text-sm max-w-md">
            Sorry, the page you are looking for does not exist or has been
            moved.
          </p>
        </div>
        <Link
          to="/"
          className="flex items-center gap-2 bg-primary hover:bg-secondary text-white px-6 py-3 rounded-full transition text-sm font-medium"
        >
          <FaArrowLeft />
          Back to Home
        </Link>
      </div>
    </section>
  );
}

export default PageNotFound;
