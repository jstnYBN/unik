npm.cmd install -g @openai/codex
codex.cmd
1

Sub InsertScreenshot()
    Selection.PasteSpecial DataType:=wdPasteBitmap, Placement:=wdInLine
    Selection.ParagraphFormat.Alignment = wdAlignParagraphCenter
    Selection.Collapse wdCollapseEnd
    Selection.TypeParagraph

    Selection.Style = wdStyleCaption
    Selection.Font.Name = "Times New Roman"
    Selection.Font.Size = 14
    Selection.Font.Bold = False
    Selection.Font.Italic = False
    Selection.Font.Color = wdColorBlack
    Selection.ParagraphFormat.Alignment = wdAlignParagraphCenter

    Selection.TypeText "Рисунок "
    Selection.Fields.Add Range:=Selection.Range, _
        Type:=wdFieldSequence, Text:="Рисунок"
    Selection.EndKey Unit:=wdLine
    Selection.TypeText " - "
End Sub


CustomizationContext = NormalTemplate: KeyBindings.Add KeyCategory:=wdKeyCategoryMacro, Command:="InsertScreenshot", KeyCode:=BuildKeyCode(wdKeyControl, wdKeyShift, wdKeyV)