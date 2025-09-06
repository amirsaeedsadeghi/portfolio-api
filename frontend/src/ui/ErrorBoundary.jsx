import { FaArrowLeft } from "react-icons/fa6";
import {
    Link,
    isRouteErrorResponse,
    useNavigate,
    useRevalidator,
    useRouteError,
} from "react-router-dom";

function ErrorBoundary() {
    const error = useRouteError();
    const navigate = useNavigate();
    const revalidator = useRevalidator();

    let status = 500;
    let title = "Something went wrong";
    let message = "An unexpected error occurred.";

    if (isRouteErrorResponse(error)) {
        status = error.status;
        title = status === 404 ? "Page Not Found" : `Error ${status}`;
        message = error.data || error.statusText || message;
    } else if (error instanceof Error) {
        message = error.message || message;
    }

    const showStack =
        import.meta?.env?.DEV && error instanceof Error && error.stack;
    return (
        <section className="min-h-screen flex flex-col justify-center items-center text-light text-center bg-cover bg-center relative px-6 page-not-found">
            <div className="absolute inset-0 bg-black bg-opacity-70"></div>
            <div className="relative z-10 flex flex-col items-center gap-8">
                <div className="perspective-container">
                    <h1 className="text-9xl font-extrabold tracking-widest text-primary fall-back depth-text">
                        {status === 404 ? 404 : "ERR"}
                    </h1>
                </div>
                <div className="bg-bg-primary px-6 py-4 rounded-lg animate-pulse">
                    <h2 className="text-3xl font-bold mb-2">{title}</h2>
                    <p className="text-sm max-w-md">
                        {status === 404
                            ? message ||
                              "The page you are looking for does not exist or has been moved."
                            : message || "Please try again or come back later."}
                    </p>
                    {showStack && (
                        <pre className="mt-4 text-left text-xs overflow-auto max-h-56 p-3 rounded bg-black/40 border border-white/10">
                            {error.stack}
                        </pre>
                    )}
                </div>
                <div className="mt-5 flex flex-wrap items-center justify-center gap-3">
                    <Link
                        to="/"
                        className="flex items-center gap-2 bg-primary hover:bg-secondary text-white px-6 py-3 rounded-full transition text-sm font-medium"
                    >
                        <FaArrowLeft />
                        Back to Home
                    </Link>
                    <button
                        className="px-5 py-3 rounded-full text-sm font-medium border border-white/20 hover:bg-white/10 transition"
                        onClick={() => navigate(-1)}
                    >
                        Go Back
                    </button>
                    <button
                        onClick={() => revalidator.revalidate()}
                        className="px-5 py-3 rounded-full text-sm font-medium border border-primary/40 hover:bg-primary/10 transition"
                    >
                        Retry
                    </button>
                </div>
            </div>
        </section>
    );
}

export default ErrorBoundary;
