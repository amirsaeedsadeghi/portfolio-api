import { useCallback, useEffect, useRef, useState } from "react";
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

    const exitShell = useCallback(() => {
        navigate("/");
    }, [navigate]);

    const executeCurrentCommand = useCallback(() => {
        if (isProcessing) {
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
    }, [isProcessing, navigate, stage]);

    useEffect(() => {
        function handleKeyDown(event) {
            if (event.key === "Escape") {
                exitShell();
                return;
            }

            if (event.key === "Enter") {
                executeCurrentCommand();
            }
        }

        window.addEventListener("keydown", handleKeyDown);

        return () => {
            window.removeEventListener("keydown", handleKeyDown);
        };
    }, [executeCurrentCommand, exitShell]);

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
        executeCurrentCommand,
        exitShell,
    };
}

export default useSocialShell;