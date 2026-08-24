import React from "react";
import { createRoot } from "react-dom/client";
import { Provider } from "react-redux";
import { BrowserRouter } from "react-router-dom";
import CssBaseline from "@mui/material/CssBaseline";
import { ThemeProvider } from "@mui/material/styles";
import App from "./components/App";
import { store } from "./store/store";
import theme from "./theme/theme";

class ErrorBoundary extends React.Component<
    { children: React.ReactNode },
    { hasError: boolean; error: Error | null }
> {
    constructor(props: { children: React.ReactNode }) {
        super(props);
        this.state = { hasError: false, error: null };
    }

    static getDerivedStateFromError(error: Error) {
        return { hasError: true, error };
    }

    componentDidCatch(error: Error, errorInfo: React.ErrorInfo) {
        console.error("React Error Boundary Caught Error:", error, errorInfo);
    }

    render() {
        if (this.state.hasError) {
            return (
                <div
                    style={{
                        padding: "24px",
                        fontFamily: "sans-serif",
                        backgroundColor: "#0f172a",
                        color: "#ffffff",
                        minHeight: "100vh",
                        display: "flex",
                        flexDirection: "column",
                        justifyContent: "center",
                        alignItems: "center",
                        textAlign: "center",
                    }}
                >
                    <h2
                        style={{
                            color: "#ef4444",
                            fontSize: "20px",
                            marginBottom: "8px",
                        }}
                    >
                        Application Render Error
                    </h2>
                    <p
                        style={{
                            fontSize: "14px",
                            color: "#94a3b8",
                            marginBottom: "16px",
                            maxWidth: "320px",
                            wordBreak: "break-word",
                        }}
                    >
                        {this.state.error?.message ||
                            "An unexpected error occurred while rendering the application."}
                    </p>
                    <button
                        onClick={() => {
                            localStorage.clear();
                            window.location.reload();
                        }}
                        style={{
                            padding: "10px 20px",
                            backgroundColor: "#6366f1",
                            color: "#ffffff",
                            border: "none",
                            borderRadius: "8px",
                            cursor: "pointer",
                            fontWeight: 600,
                        }}
                    >
                        Clear Storage & Retry
                    </button>
                </div>
            );
        }
        return this.props.children;
    }
}

const container = document.getElementById("root");

if (container) {
    const root = createRoot(container);
    const basename = window.location.pathname.startsWith("/frontend")
        ? "/frontend"
        : undefined;

    root.render(
        <React.StrictMode>
            <ErrorBoundary>
                <Provider store={store}>
                    <ThemeProvider theme={theme}>
                        <CssBaseline />
                        <BrowserRouter basename={basename}>
                            <App />
                        </BrowserRouter>
                    </ThemeProvider>
                </Provider>
            </ErrorBoundary>
        </React.StrictMode>,
    );
}
