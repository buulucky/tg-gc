  <div class="botton-con">
    <button class="button-cre-flight" id="button-cre-flight">Create Flight</button>
    <button class="button-cre-job" id="button-cre-job">Create Job</button>
  </div>
  <div id="addFlightModal" class="bg-modal-Flight">
    <div class="modal-contents">
      <div class="close">+</div>
      <h3>Create Flight</h3>
      <input type="text" id="flight_on" placeholder="Flight" oninput="convertToUppercaseTextbox('flight_on')">
      <input type="text" id="register" placeholder="A/C Reg." oninput="convertToUppercaseTextbox('register')">
      <input type="text" id="bay" placeholder="BAY" oninput="convertToUppercaseTextbox('bay')">
      <button type="submit" class="button-cre-flight" value="Add" onclick="addFlight()">Create Flight</button>
    </div>
  </div>
  <div id="addjobModal" class="bg-modal-job">
    <div class="modal-contents">
      <div class="close">+</div>
      <h3>Create Job</h3>
      <div id="dynamicListbox">
        <select class="flight-select" id="flight-select"></select>
        <!-- <div class="form-equ-group">
          <select class="equipment-model" name="equipmentType[]">
            <option value="GPU">GPU</option>
            <option value="ACU">ACU</option>
            <option value="ASU">ASU</option>
            <option value="ASU">WSU</option>
            <option value="ASU">TSU</option>
          </select>
        </div> -->
      </div>
      <div style="margin-bottom: 10px">
        <button type="button" class="btn-addEqu" onclick="addEqu()">+ เพิ่มอุปกรณ์</button>
      </div>
      <div>
        <button type="submit" class="button-cre-job" value="Add" onclick="addJob()">Create Job</button>
      </div>
    </div>
  </div>