import React, { Suspense } from "react";
import { RouterProvider, createBrowserRouter } from "react-router-dom";
import Home, { loader as homeLoader } from "./pages/Home";
import Project, { loader as projectLoader } from "./pages/Project";
import PageNotFound from "./pages/PageNotFound";
import Social, {loader as socialLoader} from "./pages/Social";
import ErrorBoundary from "./ui/ErrorBoundary";
import AppLayout, { loader as appLayoutLoader } from "./ui/AppLayout";
import { action as contactMeAction } from "./features/home/ContactMe";

const ToasterLazy = React.lazy(() =>
    import("react-hot-toast").then((m) => ({ default: m.Toaster }))
);

const router = createBrowserRouter([
    {
        id: "root",
        path: "/",
        element: <AppLayout />,
        errorElement: <ErrorBoundary />,
        loader: appLayoutLoader,
        children: [
            {
                index: true,
                element: <Home />,
                loader: homeLoader,
                action: contactMeAction,
            },
            {
                path: "projects/:projectSlug",
                element: <Project />,
                loader: projectLoader,
            },
            {
                path: "*",
                element: <PageNotFound />,
            },
        ],
    },
    {
        path: "/social",
        element: <Social />,
        loader: socialLoader,
        errorElement: <ErrorBoundary />,
    },
]);

function App() {
    return (
        <>
            <RouterProvider router={router} />
            <Suspense fallback={null}>
                <ToasterLazy
                    position="top-right"
                    reverseOrder={false}
                    gutter={12}
                    containerStyle={{ margin: "8px" }}
                    toastOptions={{
                        success: {
                            duration: 3000,
                        },
                        error: {
                            duration: 5000,
                        },
                        style: {
                            fontSize: "16px",
                            maxWidth: "500px",
                            padding: "16px 24px",
                            backgroundColor: "var(--color-grey-0)",
                            color: "var(--color-grey-700)",
                            borderRight: "5px solid var(--color-primary)",
                        },
                    }}
                />
            </Suspense>
        </>
    );
}

export default App;
