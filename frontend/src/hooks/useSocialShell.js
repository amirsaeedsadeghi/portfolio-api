import { useEffect, useRef, useState } from "react";
import { useNavigate } from "react-router-dom";

export const SHELL_STAGE = Object.freeze({
    WHOIS: 0,
    SOCIAL: 1,
    LOGOUT: 2,
    COMPLETE: 3,
});

const OUTPUT_DELAY = 220;
const NEXT_COMMAND_DELAY = 180;

function useSocialShell() {
    const navigate = useNavigate();

    const [stage, setStage] = useState(SHELL_STAGE.WHOIS);
    const [isProcessing, setIsProcessing] = useState(false);
    const [isCurrentOutputVisible, setIsCurrentOutputVisible] =
        useState(false);

    const outputTimerRef = useRef(null);
    const commandTimerRef = useRef(null);

    useEffect(() => {
        function handleKeyDown(event) {
            if (event.key === "Escape") {
                navigate("/");
                return;
            }

            if (event.key !== "Enter" || isProcessing) {
                return;
            }

            if (stage === SHELL_STAGE.COMPLETE) {
                navigate("/");
                return;
            }

            setIsProcessing(true);

            outputTimerRef.current = window.setTimeout(() => {
                setIsCurrentOutputVisible(true);

                commandTimerRef.current = window.setTimeout(() => {
                    setStage((currentStage) => currentStage + 1);
                    setIsCurrentOutputVisible(false);
                    setIsProcessing(false);
                }, NEXT_COMMAND_DELAY);
            }, OUTPUT_DELAY);
        }

        window.addEventListener("keydown", handleKeyDown);

        return () => {
            window.removeEventListener("keydown", handleKeyDown);
        };
    }, [isProcessing, navigate, stage]);

    useEffect(() => {
        return () => {
            window.clearTimeout(outputTimerRef.current);
            window.clearTimeout(commandTimerRef.current);
        };
    }, []);

    return {
        stage,
        isProcessing,
        isCurrentOutputVisible,
    };
}

export default useSocialShell;